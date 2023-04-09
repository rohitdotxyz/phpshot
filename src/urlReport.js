
// report url
const reportForm = document.querySelector("#report");

if (reportForm) {


    const reportAdv = document.querySelector("#report__advance");
    const reportOpt = document.querySelector("#report__options");


    if (reportAdv) {
        reportAdv.addEventListener("click", function (e) {
            if (reportAdv.checked) {
                delClasses(reportOpt, "hidden")
            } else {
                addClasses(reportOpt, "hidden")
            }
        })
    }

    reportForm.addEventListener("submit", function (e) {
        e.preventDefault()

        const reportUrl = reportForm["report__url"];
        const reportXsf = reportForm["report__csrf"];
        const reportMsg = reportForm["report__msg"];

        $.ajax({
            url: `${domain}/backend/urlReport.php`,
            type: "POST",
            data: {
                reportUrl: reportUrl.value,
                reportMsg: reportMsg && reportMsg.value,
                xtoken: reportXsf.value
            },
            success: function (responseData) {
                const result = JSON.parse(responseData);
                console.log(result)

                switch (result.status) {
                    case "success":
                        reportUrlSuccess(result.data)

                        // reportUrl.value = "";
                        // if (reportMsg) reportMsg.value = "";
                        break;
                    case "fail":
                        reportUrlFail(result.data)
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

        console.log("reported")
    })

    function reportUrlSuccess(successResult) {
        const errorsElm = document.querySelectorAll(".error");

        if (errorsElm.length > 0) {
            errorsElm.forEach(errorElm => {
                $(errorElm).removeClass("error")
                $(errorElm).addClass("opacity-0 translate-y-full")
            })
        }

        let reportSuccessTemplate = `<div class="bg-white flex flex-col gap-y-6 text-center text-gray-600 p-8 rounded-md transition-all duration-500">
            <h1 class="text-lg font-medium  text-gray-800">Report URL Successfully</h1>
            <div class="flex flex-col gap-y-4 justify-center">
                <div>Url report id: ${successResult.reportId}</div>
                <button class="text-sm rounded-full px-6 py-2 outline-none bg-gray-500 text-white shadow-[0px_5px_6px_0_rgba(160,160,160,0.5)] hover:scale-105 hover:-translate-y-px hover:shadow-[0px_6px_15px_0_rgba(160,160,160,0.5)] transition-all duration-500" onclick="hideReportSuccess(event)">Ok</button>
            </div>`;

        successModal.innerHTML = reportSuccessTemplate;
        showBgModal();

        delClasses(successModal, "scale-0 opacity-0");
    }

    function reportUrlFail(failResult) {
        for (const key in failResult) {

            const errorParent = "." + key;
            const frk = document.querySelector(errorParent);

            if (frk) {
                const reportClass = ".report__" + key;
                const reportError = failResult[key]

                if (failResult[key]) {
                    $(reportClass).removeClass("opacity-0 translate-y-full")
                    $(frk.lastElementChild).addClass("error")
                    $(reportClass).html(reportError)
                } else {
                    $(reportClass).addClass("opacity-0 translate-y-full")
                    $(frk.lastElementChild).removeClass("error")
                }
            }
        }
    }

    bgModal.addEventListener("click", hideReportSuccess)

    function hideReportSuccess(e) {
        hideBgModal();
        addClasses(successModal, "scale-0 opacity-0");
        document.body.classList.remove("overflow-hidden")
    }
}