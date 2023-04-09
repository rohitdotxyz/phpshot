const path = window.location.pathname.split("/")
// console.log(path)

const custom = path[path.length - 1];
// console.log(custom)

document.title = custom;

const urlprotectForm = document.querySelector("#urlprotect");
urlprotectForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const urlProtectCustom = document.querySelector("#urlprotect__custom").value;
    const urlProtectPass = document.querySelector("#urlprotect__password").value;
    const urlProtectCsrf = document.querySelector("#urlprotect__csrf").value;

    // redirectRequest(urlProtectCustom, urlProtectPass, urlProtectCsrf);
    // redirectRequest(urlProtectCustom, "IN", urlProtectPass, urlProtectCsrf);

    // var requestOptions = {
    //     method: 'GET',
    //     redirect: 'follow'
    // };

    fetch("http://ip-api.com/json", requestOptions)
        .then(response => response.json())
        .then((result) => {
            redirectRequest(custom, result.countryCode, urlProtectPass, urlProtectCsrf);
        })
        .catch(err => {
            redirectRequest(custom, "req err", urlProtectPass, urlProtectCsrf);
        })
})



let errorModalTimeout;
function openUrlDoesNotExist(errCode, errMessage) {
    let errorTemplate = `<div id="content" class="max-w-[350px] bg-white flex flex-row items-center gap-x-4 bg-red-100 text-red-900 p-4 rounded-md scale-50 opacity-0 invisible transition-all duration-300">
        <span class="text-lg font-medium">${errCode}</span>
        <span class="h-8 w-px bg-red-900"></span>
        <span class="text-sm">${errMessage}</span>`


    errorTemplate = errorTemplate + `</div > `;
    errorModal.innerHTML = errorTemplate;

    errorModalTimeout = setTimeout(function () {
        delClasses(errorModal, "opacity-0 invisible")
        delClasses(errorModal.firstElementChild, "scale-50 opacity-0 invisible")
    }, 5)
}

function showProtectedUrlForm(customCode) {
    const urlProtectTemp = urlprotectForm.innerHTML
        .replace("url__protect__custom", customCode)
        .replace("url__protect__password", "")

    urlprotectForm.innerHTML = urlProtectTemp

    delClasses(urlprotectForm, "opacity-0 scale-50 invisible")
}


function detectCountry() {
    var requestOptions = {
        method: 'GET',
        redirect: 'follow'
    };

    return fetch("http://ip-api.com/json", requestOptions)
        .then(response => response.json())
        .then(result => result.countryCode)
        .catch(error => console.log('error', error));
}


function redirectRequest(urlCode, location, urlPass, csrf) {

    $.ajax({
        url: `${domain}/backend/redirect.php`,
        type: "POST",
        data: {
            custom: urlCode,
            password: urlPass,
            _csrf: csrf,
            country: location,
            referer: document.referrer
        },
        success: function (response) {
            console.log(response)
            const result = JSON.parse(response);
            console.log(result.data)
            switch (result.status) {
                case "success":
                    addClasses(urlprotectForm, "opacity-0 scale-50 invisible")
                    setTimeout(() => {
                        window.location.assign(result.data.url)
                    }, 5000);
                    break;
                case "fail":
                    console.log(result.data)
                    if (result.data.password) {
                        showProtectedUrlForm(custom)
                        openUrlDoesNotExist(result.code, result.data.password)
                    }

                    if (result.data.custom) {
                        window.location.assign("./error.php")
                        // openUrlDoesNotExist(result.code, result.data.custom)
                    }

                    if (result.data.csrf) {
                        openUrlDoesNotExist(result.code, result.data.csrf)
                    }
                    break;
                case "error":
                    console.log(result.data)
                    window.location.assign("/")
                    break;
                default:
                    console.log(response)
                    break;
            }
        },
        error: function () {

        }
    })
}


// let userTimezone = new Intl.DateTimeFormat().resolvedOptions().timeZone;
// let countryCode = timezoneMapWithCountryCode[userTimezone];

// if (countryCode) {
//     redirectRequest(custom, countryCode);
// } else {
//     detectCountry().then(respCountryCode => {
//         redirectRequest(custom, respCountryCode);
//     })
// }


// redirectRequest(custom, "IN");


var requestOptions = {
    method: 'GET',
    redirect: 'follow'
};

fetch("http://ip-api.com/json", requestOptions)
    .then(response => response.json())
    .then((result) => {
        redirectRequest(custom, result.countryCode);
    })
    .catch(err => {
        redirectRequest(custom, "req err");
    })
