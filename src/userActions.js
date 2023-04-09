$(document).ready(function () {
    const successBox = $("#success_modal");
    const errorBox = $("#error_modal");

    // signout calls
    function signOutSuccess(data) {
        if (data.signout == 1) {
            window.location.reload();
        }
    }

    function signOutFail(data) {
        console.log(data)
        window.location.reload();
    }

    function signOutError(error) {
        console.log(data)
    }


    $("#signout").on("submit", function (e) {
        e.preventDefault();

        const signoutCSRF = $("#signout__csrf").val();

        $.ajax({
            url: `${domain}/backend/signout.php`,
            type: "POST",
            data: {
                _csrf: signoutCSRF
            },
            success: function (responseData) {
                const result = JSON.parse(responseData);
                switch (result.status) {
                    case "success":
                        console.log(result.data)
                        signOutSuccess(result.data);
                        break;
                    case "fail":
                        console.log(result.data)
                        signOutFail(result.data)
                        break;
                    case "error":
                        console.log(result.message)
                        signOutError(result.message)
                        break;
                    default:
                        console.log(responseData)
                        break;
                }
            },
            error: function (responseError) {

            }
        })
    })


    $("#menu-dropdown").on("click", function (e) {

        let elm = $(".menu-dropdown__button");
        let toggleElm = $(".menu-dropdown__content");

        if (elm.hasClass("menu-dropdown__button--active")) {
            elm.removeClass("menu-dropdown__button--active")
            toggleElm.addClass("hidden")
        } else {
            elm.addClass("menu-dropdown__button--active")
            toggleElm.removeClass("hidden")
        }
    })
})


