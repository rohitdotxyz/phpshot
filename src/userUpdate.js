const pagesUserSearch = document.querySelector(".pagination__userSearch");
const searchUserByText = document.querySelector("#search__user");
const searchUserSortBy = document.querySelector("#search__userSortBy");
const searchUserOrderBy = document.querySelector("#search__userOrderBy");


if (pagesUserSearch) {
    pagesUserSearch.addEventListener("submit", function (e) {
        e.preventDefault();
        console.log("fake form submit")
    })
}


let searchUserTimeout;
let prevUserSearchVal = "";
let lastUserPageNum = 1;

paginationUserAjaxRequest(1)

if (pagesUserSearch) {
    searchUserByText.addEventListener("input", function (e) {
        e.preventDefault();

        console.log("changed")

        const curSearchVal = searchUserByText.value;
        // if (curSearchVal == "") {
        //     if (searchUserTimeout) {
        //         clearTimeout(searchUserTimeout)
        //     }
        // }

        if (curSearchVal != "" && curSearchVal != prevUserSearchVal) {
            if (searchUserTimeout) {
                clearTimeout(searchUserTimeout)
            }

            searchUserTimeout = setTimeout(() => {
                paginationUserAjaxRequest(1)
            }, 750)
        }

        prevUserSearchVal = curSearchVal;
    })
}


if (searchUserSortBy) {
    searchUserSortBy.addEventListener("change", function () {
        paginationUserAjaxRequest()
    })
}

if (searchUserOrderBy) {
    searchUserOrderBy.addEventListener("change", function () {
        paginationUserAjaxRequest()
    })
}


function paginationUserAjaxRequest(pageNum = 1) {

    let searchText = "";
    let sortBy = "";
    let orderBy = "";

    if (searchUserByText) {
        searchText = searchUserByText.value;
    }

    if (searchUserSortBy) {
        sortBy = searchUserSortBy.value;
    }

    if (searchUserOrderBy) {
        orderBy = searchUserOrderBy.value;
    }

    lastUserPageNum = pageNum;

    $.ajax({
        url: `${domain}/backend/usersPagination.php`,
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
                    paginationUserSuccess(result.data);
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





function paginationUserSuccess(urlData) {
    const userDataTemplate = document.querySelector("#userdataTemp");
    const targetParent = document.querySelector(".pagination__users");
    console.log(targetParent)

    const {
        pagesData, dataPerPage,
        prevPage, firstPage, currentPage, lastPage, nextPage, pagesNum
    } = urlData;

    let startCount = (currentPage - 1) * dataPerPage;
    let endCount = startCount + pagesData.length;

    targetParent.innerHTML = "";

    let i = 0;
    for (let index = startCount; index < endCount; index++) {

        const fillUserDataValues = userDataTemplate.innerHTML
            .replace("user__data__sno", startCount + i + 1)
            .replaceAll("user__data__username", pagesData[i].username)
            .replace("user__data__role", roles[pagesData[i].role])
            .replace("user__data__roleName", pagesData[i].role)
            .replaceAll("user__data__email", pagesData[i].email)
            .replaceAll("user__data__country", world[pagesData[i].country])
            .replaceAll("user__data__dob", pagesData[i].dob)
            .replaceAll("user__data__id", pagesData[i].id)
            .replace("user__data__created", pagesData[i].created)
            .replace("user__data__updated", pagesData[i].updated)

        targetParent.insertAdjacentHTML("beforeend", fillUserDataValues)
        i = i + 1;
    }

    paginationUserButtons(prevPage, firstPage, currentPage, lastPage, nextPage, pagesNum);

    if (pagesData.length == 0) {
        noUrlsHtml = `<ul class="flex flex-row gap-x-4 p-4 bg-gray-50 text-gray-700 border-b border-gray-300">
            <li class="text-gray-700 grow-[1] basis-0 self-center overflow-hidden">No url found.</li>
        </ul>`;

        targetParent.innerHTML = noUrlsHtml;
    }
}



function renderUserEditForm(e, id, username, role, email, country, dob) {
    const targetParent = e.target.closest(".pagination__user");
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

    let editFormExist = targetParent.querySelector(".pagination__useredit");

    if (!editFormExist) {
        const userEditForm = document.querySelector("#edituserForm");

        let rolesHtml = renderRoles(roles, role)
        let countriesHtml = renderCountries(world, country)

        const fillEditFormValues = userEditForm.innerHTML
            .replace("update__user__id", id)
            .replace("update__user__username", username)
            .replace("update__user__email", email)
            .replace("update__user__role", rolesHtml)
            .replace("update__user__country", countriesHtml)
            .replace("update__user__dob", dob)


        targetParent.insertAdjacentHTML("beforeEnd", fillEditFormValues)
        editFormExist = targetParent.querySelector(".pagination__useredit");
    }


    // if (editFormExist && editFormExist.classList.contains("hidden")) {
    //     editFormExist.classList.remove("hidden");
    // } else {
    //     editFormExist.classList.add("hidden");
    // }

    console.log(editFormExist)

    if (editFormExist && editFormExist.style.maxHeight) {
        editFormExist.style.maxHeight = null;
    } else {
        editFormExist.style.maxHeight = editFormExist.scrollHeight + "px";
    }
}

function renderRoles($obj, $select) {
    let optionHtml = "";

    for (const key in $obj) {
        if (key == 100) {
            continue;
        }

        if (key == $select || $obj[key] == $select) {
            optionHtml += `<option value="${key}" selected>${$obj[key]}</option>`
        } else {
            optionHtml += `<option value="${key}">${$obj[key]}</option>`
        }
    }

    return optionHtml
}

function renderCountries($obj, $select) {
    let optionHtml = "";

    for (const key in $obj) {
        if (key == $select || $obj[key] == $select) {
            optionHtml += `<option value="${key}" selected>${$obj[key]}</option>`
        } else {
            optionHtml += `<option value="${key}">${$obj[key]}</option>`
        }
    }

    return optionHtml
}

function renderUserDelForm(e, id, username) {
    const targetParent = e.target.closest(".pagination__user");
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

    let delFormExist = targetParent.querySelector(".pagination__userdelete");

    if (!delFormExist) {
        const userDelForm = document.querySelector("#deluserForm");

        const fillDelFormValues = userDelForm.innerHTML
            .replace("delete__user__id", id)
            .replace("delete__user__username", username)

        targetParent.insertAdjacentHTML("beforeEnd", fillDelFormValues)
        delFormExist = targetParent.querySelector(".pagination__userdelete");
    }

    delClasses(delFormExist, "hidden")
    document.body.classList.add("overflow-hidden")


    setTimeout(function () {
        delClasses(delFormExist.firstElementChild, "opacity-0")
        delClasses(delFormExist.firstElementChild.firstElementChild, "scale-0")
    }, 5)
}

function hideUserDelForm(e, deleteForm = false) {
    const targetElement = e.target.closest(".pagination__user");

    if (!targetElement) {
        return false;
    }

    const delButton = targetElement.querySelector(".button__del--opened");
    const delForm = targetElement.querySelector(".pagination__userdelete");

    delClasses(delButton, "button__del--opened bg-red-600")
    addClasses(delButton, "button__del--closed bg-red-500")
    addClasses(delForm.firstElementChild, "opacity-0")
    addClasses(delForm.firstElementChild.firstElementChild, "scale-0")
    document.body.classList.remove("overflow-hidden")


    const errorDel = targetElement.querySelector("#deleteuser__error");

    setTimeout(function () {
        if (deleteForm) {
            targetElement.remove();
        }

        errorDel.innerHTML = ""
        errorDel.classList.add("hidden")
        addClasses(delForm, "hidden")
    }, 500)
}

function prevUserPage(prevPg) {
    paginationUserAjaxRequest(prevPg)
}

function nextUserPage(nextPg) {
    paginationUserAjaxRequest(nextPg)
}

function clickUserPage(clickedPg) {
    paginationUserAjaxRequest(clickedPg)
}

function paginationUserButtons(prevPg, firstPg, currentPg, lastPg, nextPg, allPgs) {
    const pagesButtonsContainer = document.querySelector(".pagination__userbuttons");

    let disablePrevPage = "";
    let disableNextPage = "";
    if (prevPg == currentPg) {
        disablePrevPage = "disabled";
    }

    let paginationButtonsHtml = `<button class="pagination__prev bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm disabled:bg-gray-500" onclick="prevUserPage(${prevPg})" ${disablePrevPage}>Prev</button>`;

    allPgs.forEach((page) => {
        displayPage = page < 10 ? "0" + page : page;
        if (currentPg == page) {
            paginationButtonsHtml = paginationButtonsHtml + `<button class="pagination__page bg-sky-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm" onclick="clickUserPage(${page})">${displayPage}</button>`
        } else {
            paginationButtonsHtml = paginationButtonsHtml + `<button class="pagination__page bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm" onclick="clickUserPage(${page})">${displayPage}</button>`
        }
    });

    if (nextPg == currentPg) {
        disableNextPage = "disabled";
    }

    paginationButtonsHtml = paginationButtonsHtml + `<button class="pagination__next bg-gray-700 hover:bg-sky-500 px-2.5 py-0.5 rounded-sm disabled:bg-gray-500" onclick="nextUserPage(${nextPg})" ${disableNextPage}>Next</button>`;
    pagesButtonsContainer.innerHTML = paginationButtonsHtml;
}

function updateUserForm(e) {
    e.preventDefault();

    const parentElm = e.target.closest(".pagination__user");
    const targetForm = e.currentTarget;

    const id = targetForm.querySelector("#updateuser__id").value;
    const newUsername = targetForm.querySelector("#updateuser__username").value;
    const newEmail = targetForm.querySelector("#updateuser__email").value;
    const newRole = targetForm.querySelector("#updateuser__role").value;
    const newPassword = targetForm.querySelector("#updateuser__password").value;
    const newCountry = targetForm.querySelector("#updateuser__country").value;
    const newDob = targetForm.querySelector("#updateuser__dob").value;
    const xtoken = targetForm.querySelector("#updateuser__xtoken").value;

    console.log(newEmail)


    $.ajax({
        url: `${domain}/backend/userUpdate.php`,
        type: "POST",
        data: {
            id: id,
            username: newUsername,
            email: newEmail,
            role: newRole,
            password: newPassword,
            country: newCountry,
            dob: newDob,
            xtoken: xtoken,
        },
        success: function (responseData) {
            const result = JSON.parse(responseData);
            console.log(result)

            switch (result.status) {
                case "success":
                    updateUserSuccess(e, result.data)
                    paginationUserAjaxRequest(lastUserPageNum)
                    break;
                case "fail":
                    updateUserFail(e, result.data)
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


function updateUserSuccess(e, data) {
    for (const key in data) {
        const updateClass = ".updateuser__" + key;
        const updateError = key + " " + data[key]

        addClasses(e.target.querySelector(updateClass), "opacity-0 translate-y-full")
    }


    const errorDel = e.target.querySelector("#updateuser__error");
    errorDel.classList.add("hidden")
    errorDel.innerHTML = ""
}

function updateUserFail(e, data) {
    for (const key in data) {
        const updateClass = ".updateuser__" + key;
        const updateError = data[key]

        let targetElm = e.target.querySelector(updateClass);
        if (data[key] && targetElm) {
            delClasses(targetElm, "opacity-0 translate-y-full")
            targetElm.innerHTML = updateError
        } else {
            addClasses(targetElm, "opacity-0 translate-y-full")
        }
    }


    const targetElement = e.target.closest(".pagination__user");
    console.log(targetElement)

    if (!targetElement || !data.update) {
        return false;
    }

    const errorDel = targetElement.querySelector("#updateuser__error");
    errorDel.classList.remove("hidden")
    errorDel.innerHTML = data.update
}


// function userDelFormSubmit(e) {
//     e.preventDefault()
//     hideuserDelForm(e, true)

//     console.log(e.target)
//     console.log(e.currentTarget.querySelector("#deleteurl__csrf").value)
// }

function deleteUserForm(e) {
    e.preventDefault();

    const targetForm = e.currentTarget;

    const userId = targetForm.querySelector("#deleteuser__userId").value;
    const xtoken = targetForm.querySelector("#deleteuser__xtoken").value;

    $.ajax({
        url: `${domain}/backend/userDelete.php`,
        type: "POST",
        data: {
            userId: userId,
            xtoken: xtoken,
        },
        success: function (responseData) {
            const result = JSON.parse(responseData);
            console.log(result)

            switch (result.status) {
                case "success":
                    paginationUserAjaxRequest(lastUserPageNum)
                    document.body.classList.remove("overflow-hidden")
                    break;
                case "fail":
                    deleteUserFail(e, result.data)
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


function deleteUserFail(e, data) {
    const targetElement = e.target.closest(".pagination__user");

    if (!targetElement) {
        return false;
    }

    const errorDel = targetElement.querySelector("#deleteuser__error");
    errorDel.classList.remove("hidden")
    errorDel.innerHTML = data.delete
}