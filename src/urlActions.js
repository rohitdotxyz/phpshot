const pagesURLSearch = document.querySelector(".pagination__urlSearch");
const searchURLByText = document.querySelector("#search__url");
const searchURLSortBy = document.querySelector("#search__urlSortBy");
const searchURLOrderBy = document.querySelector("#search__urlOrderBy");


if (pagesURLSearch) {
    pagesURLSearch.addEventListener("submit", function (e) {
        e.preventDefault();
        console.log("fake form submit")
    })
}


let searchTimeout;
let prevSearchVal = "";
let lastURLPageNum = 1;

paginationURLAjaxRequest(1)

if (pagesURLSearch) {
    searchURLByText.addEventListener("input", function (e) {
        e.preventDefault();

        console.log("changed")

        const curSearchVal = searchURLByText.value;
        // if (curSearchVal == "") {
        //     if (searchTimeout) {
        //         clearTimeout(searchTimeout)
        //     }
        // }

        if (curSearchVal != "" && curSearchVal != prevSearchVal) {
            if (searchTimeout) {
                clearTimeout(searchTimeout)
            }

            searchTimeout = setTimeout(() => {
                paginationURLAjaxRequest(1)
            }, 750)
        }

        prevSearchVal = curSearchVal;
    })
}


if (searchURLSortBy) {
    searchURLSortBy.addEventListener("change", function () {
        paginationURLAjaxRequest()
    })
}

if (searchURLOrderBy) {
    searchURLOrderBy.addEventListener("change", function () {
        paginationURLAjaxRequest()
    })
}


function paginationURLAjaxRequest(pageNum = 1) {

    let searchText = "";
    let sortBy = "";
    let orderBy = "";

    if (searchURLByText) {
        searchText = searchURLByText.value;
    }

    if (searchURLSortBy) {
        sortBy = searchURLSortBy.value;
    }

    if (searchURLOrderBy) {
        orderBy = searchURLOrderBy.value;
    }

    lastURLPageNum = pageNum;

    $.ajax({
        url: `${domain}/backend/urlPagination.php`,
        type: "POST",
        data: {
            pageNum: pageNum,
            searchText: searchText,
            sortBy: sortBy,
            orderBy: orderBy,
        },
        success: function (responseData) {
            const result = JSON.parse(responseData);
            // console.log(result);

            switch (result.status) {
                case "success":
                    console.log(result.data)
                    paginationURLSuccess(result.data);
                    break;
                case "fail":
                    console.log(result.data)
                    paginationFail(result.data)
                    break;
                case "error":
                    console.log(result.message)
                    paginationError(result.message)
                    break;
                default:
                    console.log(response)
                    break;
            }

        },
        error: function (responseError) {

        }
    })
}


function paginationURLSuccess(urlData) {
    const urlDataTemplate = document.querySelector("#urldataTemp");
    const targetParent = document.querySelector(".pagination__urls");

    const {
        domain, userId, pagesData, dataPerPage,
        prevPage, firstPage, currentPage, lastPage, nextPage, pagesNum
    } = urlData;

    let startCount = (currentPage - 1) * dataPerPage;
    let endCount = startCount + pagesData.length;

    targetParent.innerHTML = "";

    let i = 0;
    for (let index = startCount; index < endCount; index++) {

        let shortUrl = `${domain}/${pagesData[i].hunterCode}`;
        // let shortUrl = `${domain}/${pagesData[i].hunterCode}`.replace(/^(http|https):\/\/(www\.)?/, "");
        let statsUrl = `${domain}/stats/${pagesData[i].hunterCode}`;
        let username = pagesData[i].username ? pagesData[i].username : "no name";

        let urlKey = "";
        if (pagesData[i].protected) {
            urlKey = "fa fa-lock fa-xs";
        } else {
            urlKey = "fa fa-lock-open fa-xs";
        }

        const fillUrlDataValues = urlDataTemplate.innerHTML
            .replace("url__data__sno", startCount + i + 1)
            .replaceAll("url__data__hunterUrl", shortUrl)
            .replace("url__data__views", pagesData[i].totalVisits)
            .replace("url__data__createdOn", pagesData[i].createdAt)
            .replaceAll("url__data__targetUrl", pagesData[i].targetUrl)
            .replaceAll("url__data__hunterCode", pagesData[i].hunterCode)
            .replace("url__data__report", pagesData[i].report)
            .replace("url__data__updatedOn", pagesData[i].updatedAt)
            .replaceAll("url__data__createdById", pagesData[i].createdBy)
            .replaceAll("url__data__createdByUsername", username)
            .replace("url__data__protected", urlKey)
            .replaceAll("url__data__createdById", userId)
            .replaceAll("url__data__urlId", pagesData[i].linkId)
            .replace("url__data__deleted", pagesData[i].deleted)
            .replace("url__data__stats", statsUrl)

        targetParent.insertAdjacentHTML("beforeend", fillUrlDataValues)
        i = i + 1;
    }

    paginationURLButtons(prevPage, firstPage, currentPage, lastPage, nextPage, pagesNum);

    if (pagesData.length == 0) {
        noUrlsHtml = `<ul class="flex flex-row gap-x-4 p-4 bg-gray-50 text-gray-700 border-b border-gray-300">
            <li class="text-gray-700 grow-[1] basis-0 self-center overflow-hidden">No url found.</li>
        </ul>`;

        targetParent.innerHTML = noUrlsHtml;
    }
}



function renderUrlEditForm(e, urlId, targetUrl, hunterUrl) {
    const targetParent = e.target.closest(".pagination__url");
    const targetElement = e.currentTarget;
    const isFormOpened = targetElement.classList.contains("button__edit--opened");

    if (!targetParent) {
        return false;
    }

    if (isFormOpened) {
        delClasses(targetElement, "button__edit--opened bg-yellow-600")
        addClasses(targetElement, "button__edit--closed bg-yellow-500")
    } else {
        delClasses(targetElement, "button__edit--closed bg-yellow-500")
        addClasses(targetElement, "button__edit--opened bg-yellow-600")
    }

    let editFormExist = targetParent.querySelector(".pagination__urledit");

    if (!editFormExist) {
        const urlEditForm = document.querySelector("#editurlForm");

        const fillEditFormValues = urlEditForm.innerHTML
            .replace("update__url__linkId", urlId)
            .replace("update__url__target", targetUrl)
            .replace("update__url__custom", hunterUrl)

        targetParent.insertAdjacentHTML("beforeEnd", fillEditFormValues)
        editFormExist = targetParent.querySelector(".pagination__urledit");
    }


    // if (editFormExist && editFormExist.classList.contains("hidden")) {
    //     editFormExist.classList.remove("hidden");
    // } else {
    //     editFormExist.classList.add("hidden");
    // }

    if (editFormExist && editFormExist.style.maxHeight) {
        editFormExist.style.maxHeight = null;
    } else {
        editFormExist.style.maxHeight = editFormExist.scrollHeight + "px";
    }
}

function renderUrlDelForm(e, urlId, targetUrl, hunterUrl) {
    const targetParent = e.target.closest(".pagination__url");
    const targetElement = e.currentTarget;
    const isFormOpened = targetElement.classList.contains("button__del--opened");

    if (!targetParent) {
        return false;
    }

    if (isFormOpened) {
        delClasses(targetElement, "button__del--opened bg-red-600")
        addClasses(targetElement, "button__del--closed bg-red-500")
    } else {
        delClasses(targetElement, "button__del--closed bg-red-500")
        addClasses(targetElement, "button__del--opened bg-red-600")
    }

    let delFormExist = targetParent.querySelector(".pagination__urldelete");

    if (!delFormExist) {
        const urlDelForm = document.querySelector("#delurlForm");

        const fillDelFormValues = urlDelForm.innerHTML
            .replace("delete__url__linkId", urlId)
            .replace("delete__url__code", hunterUrl)

        targetParent.insertAdjacentHTML("beforeEnd", fillDelFormValues)
        delFormExist = targetParent.querySelector(".pagination__urldelete");
    }

    delClasses(delFormExist, "hidden")
    document.body.classList.add("overflow-hidden")

    setTimeout(function () {
        delClasses(delFormExist.firstElementChild, "opacity-0")
        delClasses(delFormExist.firstElementChild.firstElementChild, "scale-0")
    }, 5)
}

function hideUrlDelForm(e, deleteForm = false) {
    const targetElement = e.target.closest(".pagination__url");

    if (!targetElement) {
        return false;
    }

    const delButton = targetElement.querySelector(".button__del--opened");
    const delForm = targetElement.querySelector(".pagination__urldelete");

    delClasses(delButton, "button__del--opened bg-red-600")
    addClasses(delButton, "button__del--closed bg-red-500")
    addClasses(delForm.firstElementChild, "opacity-0")
    addClasses(delForm.firstElementChild.firstElementChild, "scale-0")
    document.body.classList.remove("overflow-hidden")

    const errorDel = targetElement.querySelector("#deleteurl__error");


    setTimeout(function () {
        if (deleteForm) {
            targetElement.remove();
        }

        errorDel.innerHTML = ""
        errorDel.classList.add("hidden")
        addClasses(delForm, "hidden")
    }, 500)
}

function prevUrlPage(prevPg) {
    paginationURLAjaxRequest(prevPg)
}

function nextUrlPage(nextPg) {
    paginationURLAjaxRequest(nextPg)
}

function clickUrlPage(clickedPg) {
    paginationURLAjaxRequest(clickedPg)
}

function paginationURLButtons(prevPg, firstPg, currentPg, lastPg, nextPg, allPgs) {
    const pagesButtonsContainer = document.querySelector(".pagination__buttons");

    let disablePrevPage = "";
    let disableNextPage = "";
    if (prevPg == currentPg) {
        disablePrevPage = "disabled";
    }

    let paginationButtonsHtml = `<button class="pagination__prev bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm disabled:bg-gray-500" onclick="prevUrlPage(${prevPg})" ${disablePrevPage}>Prev</button>`;

    allPgs.forEach((page) => {
        displayPage = page < 10 ? "0" + page : page;
        if (currentPg == page) {
            paginationButtonsHtml = paginationButtonsHtml + `<button class="pagination__page bg-sky-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm" onclick="clickUrlPage(${page})">${displayPage}</button>`
        } else {
            paginationButtonsHtml = paginationButtonsHtml + `<button class="pagination__page bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm" onclick="clickUrlPage(${page})">${displayPage}</button>`
        }
    });

    if (nextPg == currentPg) {
        disableNextPage = "disabled";
    }

    paginationButtonsHtml = paginationButtonsHtml + `<button class="pagination__next bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm disabled:bg-gray-500" onclick="nextUrlPage(${nextPg})" ${disableNextPage}>Next</button>`;
    pagesButtonsContainer.innerHTML = paginationButtonsHtml;
}

function updateUrlForm(e) {
    e.preventDefault();

    const parentElm = e.target.closest(".pagination__url");
    const targetForm = e.currentTarget;

    const linkId = targetForm.querySelector("#updateurl__linkId").value;
    const xtoken = targetForm.querySelector("#updateurl__xtoken").value;
    const newTarget = targetForm.querySelector("#updateurl__target");
    const newHunter = targetForm.querySelector("#updateurl__custom");
    const newSecret = targetForm.querySelector("#updateurl__password");

    let newTargetVal = "";
    if (newTarget) {
        newTargetVal = newTarget.value;
    }

    let newHunterVal = "";
    if (newHunter) {
        newHunterVal = newHunter.value;
    }

    let newSecretVal = "";
    if (newSecret) {
        newSecretVal = newSecret.value;
    }

    $.ajax({
        url: `${domain}/backend/urlUpdate.php`,
        type: "POST",
        data: {
            target: newTargetVal,
            hunter: newHunterVal,
            secret: newSecretVal,
            linkId: linkId,
            xtoken: xtoken,
        },
        success: function (responseData) {
            const result = JSON.parse(responseData);
            console.log(result)

            switch (result.status) {
                case "success":
                    updateUrlSuccess(e, result.data)
                    paginationURLAjaxRequest(lastURLPageNum)
                    break;
                case "fail":
                    updateUrlFail(e, result.data)
                    break;
                case "error":
                    break;
                default:
                    break;
            }
        },
        error: function (responseError) {
            console.log(responseError)
        }
    })
}


function updateUrlSuccess(e, data) {
    console.log(e)
    for (const key in data) {
        const updateClass = ".updateurl__" + key;
        const updateError = key + " " + data[key]

        addClasses(e.target.querySelector(updateClass), "opacity-0 translate-y-full")
    }
}

function updateUrlFail(e, data) {
    for (const key in data) {
        const updateClass = ".updateurl__" + key;
        const updateError = data[key]

        if (data[key]) {
            delClasses(e.target.querySelector(updateClass), "opacity-0 translate-y-full")
            e.target.querySelector(updateClass).innerHTML = updateError
        } else {
            addClasses(e.target.querySelector(updateClass), "opacity-0 translate-y-full")
        }
    }
}


// function urlDelFormSubmit(e) {
//     e.preventDefault()
//     hideUrlDelForm(e, true)

//     console.log(e.target)
//     console.log(e.currentTarget.querySelector("#deleteurl__csrf").value)
// }

function deleteUrlForm(e) {
    e.preventDefault();

    const targetForm = e.currentTarget;

    const linkId = targetForm.querySelector("#deleteurl__linkId").value;
    const xtoken = targetForm.querySelector("#deleteurl__xtoken").value;

    $.ajax({
        url: `${domain}/backend/urlDelete.php`,
        type: "POST",
        data: {
            linkId: linkId,
            xtoken: xtoken,
        },
        success: function (responseData) {
            const result = JSON.parse(responseData);
            console.log(result)

            switch (result.status) {
                case "success":
                    paginationURLAjaxRequest(lastURLPageNum)
                    document.body.classList.remove("overflow-hidden")
                    break;
                case "fail":
                    deleteUrlFail(e, result.data)
                    break;
                case "error":
                    break;
                default:
                    break;
            }

        },
        error: function (responseError) {
            console.log(responseError)
        }
    })
}




function deleteUrlFail(e, data) {
    const targetElement = e.target.closest(".pagination__url");

    if (!targetElement) {
        return false;
    }

    const errorDel = targetElement.querySelector("#deleteurl__error");
    errorDel.classList.remove("hidden")
    errorDel.innerHTML = data.delete
}