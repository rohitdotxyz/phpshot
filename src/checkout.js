// This is your test publishable API key.
$(document).ready(function (e) {

    const csrf = document.querySelector("#subs__xtoken").value;

    var requestOptions = {
        method: 'GET',
        redirect: 'follow'
    };


    $("#subs").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            url: `${domain}/backend/checkout.php`,
            type: "POST",
            data: {
                xtoken: csrf,
            },
            success: function (response) {
                console.log(response)
                const result = JSON.parse(response);

                switch (result.status) {
                    case "success":
                        // console.log(result)
                        window.location.assign(result.data.url)
                        break;
                    case "fail":

                    case "error":

                        break;
                    default:
                        console.log(response)
                        break;
                }
            },
            error: function () {

            }
        })

    })

})

