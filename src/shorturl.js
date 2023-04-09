const shorturlForm = document.querySelector("#shorturl");

const shorturlFormSubmit = document.querySelector("#shorturl__submit");

const generatedBox = document.querySelector("#generated")
const generatedLink = document.querySelector("#generated__link");
const generatedCopy = document.querySelector("#generated__copy");



let shorturlTimeout;

if (shorturlForm) {
    const advance = document.querySelector("#shorturl__advance");
    const options = document.querySelector("#shorturl__options");

    if (advance) {
        advance.addEventListener("click", function (e) {
            if (advance.checked) {
                delClasses(options, "hidden")
            } else {
                addClasses(options, "hidden")
            }
        })
    }

    shorturlForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const target = document.querySelector("#shorturl__target");
        const hunter = document.querySelector("#shorturl__hunter");
        const secret = document.querySelector("#shorturl__secret");
        const token = document.querySelector("#shorturl__xtoken");


        shorturlFormSubmit.disabled = true;
        shorturlTimeout = setTimeout(() => {
            shorturlFormSubmit.disabled = false;
        }, 5000);

        $.ajax({
            url: `${domain}/backend/shorturl.php`,
            type: "POST",
            data: {
                target: target.value,
                xtoken: token.value,
                hunter: hunter && hunter.value,
                secret: secret && secret.value,
            },
            success: function (response) {
                const result = JSON.parse(response);
                console.log(result)

                switch (result.status) {
                    case "success":
                        shorturlSuccess(result.data);
                        target.value = "";
                        if (hunter) hunter.value = "";
                        if (secret) secret.value = "";
                        break;
                    case "fail":
                        shorturlFail(result.data)
                        clearTimeout(shorturlTimeout);
                        shorturlFormSubmit.disabled = false;
                        break;
                    case "error":

                        break;
                    default:
                        console.log(response)
                        break;
                }
            },
            error: function (message) {

            }
        })
    })


    function shorturlSuccess(successResult) {
        const errorsElm = document.querySelectorAll(".error");

        if (errorsElm.length > 0) {
            errorsElm.forEach(errorElm => {
                delClasses(errorElm, "error")
                addClasses(errorElm, "opacity-0 translate-y-full")
            })
        }

        delClasses(generatedBox, "hidden")

        generatedLink.href = successResult.url;
        generatedLink.innerHTML = successResult.url;

        if (!window.isSecureContext) {
            generatedCopy.classList.add("hidden")
        }
    }

    let linkCopiedTimeout;
    generatedCopy.addEventListener("click", function (e) {
        const linkValue = generatedLink.href;
        navigator.clipboard.writeText(linkValue);

        generatedCopy.value = "Copied";
        generatedCopy.disabled = true;

        if (linkCopiedTimeout) {
            return false;
        }

        linkCopiedTimeout = setTimeout(function () {
            linkCopiedTimeout = null;
            generatedCopy.value = "Copy";
            generatedCopy.disabled = false;
        }, 2000)
    })

    function shorturlFail(failResult) {
        const failResultKeys = Object.keys(failResult);

        failResultKeys.forEach(failResultKey => {
            const errorClass = "." + failResultKey;
            const frk = document.querySelector(errorClass);

            if (frk) {
                if (failResult[failResultKey]) {
                    frk.lastElementChild.innerHTML = failResult[failResultKey]
                    addClasses(frk.lastElementChild, "error")
                    delClasses(frk.lastElementChild, "opacity-0 translate-y-full")
                } else {
                    addClasses(frk.lastElementChild, "opacity-0 translate-y-full")
                    delClasses(frk.lastElementChild, "error")
                }
            }
        })
    }
}
