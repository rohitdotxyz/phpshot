$(document).ready(function (e) {

    $("#updateuser").on("submit", function (e) {
        e.preventDefault();

        const username = $("#updateuser__username");
        const email = $("#updateuser__email");
        const country = $("#updateuser__country");
        const dob = $("#updateuser__dob");
        const password = $("#updateuser__password");
        const xToken = $("#updateuser__xtoken");


        $.ajax({
            url: `${domain}/backend/profileUpdate.php`,
            type: "POST",
            data: {
                username: username.val(),
                email: email.val(),
                country: country.val(),
                dob: dob.val(),
                xtoken: xToken.val(),
                password: password.val()
            },
            success: function (responseData) {
                const result = JSON.parse(responseData);
                console.log(result)

                switch (result.status) {
                    case "success":
                        updateUserSuccess(result.data)
                        break;
                    case "fail":
                        updateUserFail(result.data)
                        break;
                    case "error":
                        break;
                    default:
                        break;
                }
            }
        })
    })


    function updateUserSuccess(data) {
        $('[class*="updateuser__"]').addClass("opacity-0 translate-y-full")
        $("#updateuser__password").val("")
    }


    function updateUserFail(data) {
        for (const key in data) {
            const updateClass = ".updateuser__" + key;
            const updateError = data[key]

            if (data[key]) {
                $(updateClass).removeClass("opacity-0 translate-y-full")
                $(updateClass).html(updateError)
            } else {
                $(updateClass).addClass("opacity-0 translate-y-full")
            }
        }
    }



    $("#updatepass").on("submit", function (e) {
        e.preventDefault();

        const oldpass = $("#updatepass__cpassword");
        const newpass = $("#updatepass__npassword");
        const xToken = $("#updatepass__xtoken");

        $.ajax({
            url: `${domain}/backend/updatePass.php`,
            type: "POST",
            data: {
                oldpass: oldpass.val(),
                newpass: newpass.val(),
                xtoken: xToken.val(),
            },
            success: function (responseData) {
                const result = JSON.parse(responseData);
                console.log(result)

                switch (result.status) {
                    case "success":
                        updateUserPassSuccess(result.data)
                        oldpass.val("");
                        newpass.val("");
                        break;
                    case "fail":
                        updateUserPassFail(result.data)
                        break;
                    case "error":
                        break;
                    default:
                        break;
                }
            }
        })
    })



    function updateUserPassSuccess(data) {
        $('[class*="updatepass__"]').addClass("opacity-0 translate-y-full")
        $("#updatepass__password").val("")
    }


    function updateUserPassFail(data) {
        for (const key in data) {
            const updateClass = ".updatepass__" + key;
            const updateError = data[key]

            if (data[key]) {
                $(updateClass).removeClass("opacity-0 translate-y-full")
                $(updateClass).html(updateError)
            } else {
                $(updateClass).addClass("opacity-0 translate-y-full")
            }
        }
    }

})