// tabs
const authForms = document.querySelector(".auth-forms");
const tabsContainer = document.querySelector(".tabs");

tabsContainer.addEventListener("click", function (e) {
    const curTab = e.target.closest(".tab");
    if (curTab === null) {
        return false;
    }

    const tabs = tabsContainer.querySelectorAll(".tab");
    let prevTab;
    tabs.forEach(function (tab) {
        if (tab.classList.contains("tab--active")) {
            prevTab = tab;
        }

        tab.classList.remove("tab--active");
    })

    curTab.classList.add("tab--active")
    const toHide = "." + prevTab.dataset.tab;
    const toShow = "." + curTab.dataset.tab;


    if (toHide == toShow) {
        return false;
    }


    const hideAll = authForms.querySelectorAll(toHide)
    hideAll.forEach(hideEach => {
        hideEach.classList.add("hidden")
    })

    const showAll = authForms.querySelectorAll(toShow)
    showAll.forEach(showEach => {
        showEach.classList.remove("hidden")
    })
})

// hide overlay
authForms.addEventListener("click", hideAuthForms)

// show AuthForms
function showAuthForms() {
    authForms.classList.remove("opacity-0")
    authForms.classList.remove("invisible")
    authForms.firstElementChild.classList.remove("scale-150")
    authForms.firstElementChild.classList.remove("opacity-0")
}

function hideAuthForms(e) {

    // console.log(e.target)

    if (!e.target.classList.contains("auth-forms")) {
        return false;
    }

    authForms.firstElementChild.classList.add("scale-150")
    authForms.firstElementChild.classList.add("opacity-0")
    authForms.classList.add("opacity-0")
    authForms.classList.add("invisible")
}

// navbar
const nav = document.querySelector(".nav")
nav.addEventListener("click", function (e) {

    const isBtn = e.target.closest(".nav__btn")
    if (isBtn === null) {
        return false;
    }

    const whichButton = isBtn.dataset.tab;

    const tabs = tabsContainer.querySelectorAll(".tab");
    let prevTab = null;
    let curTab = null;

    tabs.forEach(function (eachTab) {
        if (eachTab.classList.contains("tab--active")) {
            prevTab = eachTab;
        }

        eachTab.classList.remove("tab--active");

        if (eachTab.dataset.tab == whichButton) {
            curTab = eachTab;
        }
    })

    if (curTab === null) {
        return false;
    }


    // showBgModal();
    showAuthForms()

    curTab.classList.add("tab--active")

    if (prevTab == null) {
        return false;
    }

    const toHide = "." + prevTab.dataset.tab;
    const toShow = "." + curTab.dataset.tab;

    if (toHide == toShow) {
        return false;
    }

    const hideAll = authForms.querySelectorAll(toHide)
    hideAll.forEach(hideEach => {
        hideEach.classList.add("hidden")
    })

    const showAll = authForms.querySelectorAll(toShow)
    showAll.forEach(showEach => {
        showEach.classList.remove("hidden")
    })
})



$(document).ready(function () {

    // Signin calls
    function signInSuccess(data) {
        if (data.username) {
            window.location.reload();
        }
    }

    function signInFail(data) {
        for (const key in data) {
            const signInClass = ".signin__" + key;
            const signInError = data[key];

            if (signInError) {
                $(signInClass).removeClass("opacity-0 translate-y-full")
                $(signInClass).html(signInError)
            } else {
                $(signInClass).addClass("opacity-0 translate-y-full")
            }
        }
    }

    function signInError(error) {
        // window.location.reload();
    }


    $("#signin").on("submit", function (e) {
        e.preventDefault()

        const username = $("#signin__username").val();
        const password = $("#signin__password").val();
        const signinCSRF = $("#signin__csrf").val();

        $.ajax({
            url: `${domain}/backend/signin.php`,
            type: "POST",
            data: {
                username: username,
                password: password,
                _csrf: signinCSRF,
            },
            success: function (response) {
                const result = JSON.parse(response);

                switch (result.status) {
                    case "success":
                        console.log(result.data)
                        signInSuccess(result.data);
                        break;
                    case "fail":
                        console.log(result.data)
                        signInFail(result.data)
                        break;
                    case "error":
                        console.log(result.message)
                        signInError(result.message)
                        break;
                    default:
                        console.log(response)
                        break;
                }
            },
            error: function (response) {
                console.log(response)
            }
        })
    })


    // Signup calls
    function signUpSuccess(data) {
        if (data.username) {
            window.location.reload();
        }
    }

    function signUpFail(data) {
        for (const key in data) {
            const signupClass = ".signup__" + key;
            const signupError = key + " " + data[key];

            if (data[key]) {
                $(signupClass).removeClass("opacity-0 translate-y-full")
                $(signupClass).html(signupError)
            } else {
                $(signupClass).addClass("opacity-0 translate-y-full")
            }
        }
    }

    function signUpError(error) {
        // window.location.reload();
    }

    $("#signup").on("submit", function (e) {
        e.preventDefault()

        const username = $("#signup__username").val();
        const email = $("#signup__email").val();
        const password = $("#signup__password").val();
        const country = $("#signup__country").val();
        const dob = $("#signup__dob").val();
        const signupCSRF = $("#signup__csrf").val();

        $.ajax({
            url: `${domain}/backend/signup.php`,
            type: "POST",
            data: {
                username: username,
                email: email,
                password: password,
                dob: dob,
                country: country,
                _csrf: signupCSRF
            },
            success: function (response) {
                const result = JSON.parse(response);

                switch (result.status) {
                    case "success":
                        console.log(result.data)
                        signUpSuccess(result.data);
                        break;
                    case "fail":
                        console.log(result.data)
                        signUpFail(result.data)
                        break;
                    case "error":
                        console.log(result.message)
                        signUpError(result.message)
                        break;
                    default:
                        console.log(response)
                        break;
                }
            },
            error: function (response) {
                console.log(response)
            }
        })
    })
})