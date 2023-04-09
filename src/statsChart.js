// line chart
const clickChartElm = document.getElementById('clicksData');

const clicksChartData = {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"],
    datasets: [{
        label: 'Views',
        data: [110, 85, 98, 50, 131, 181, 121, 189, 169, 195, 54, 46],
        tension: 0.4,
        fill: true,
        backgroundColor: "red",
        pointRadius: 2,
        pointStyle: 'circle',
        pointBackgroundColor: 'rgb(255, 99, 132)',
        borderWidth: 2,
        borderColor: 'rgb(255, 99, 132, 0.8)',
    }],
};


const clickChartconfig = {
    type: 'line',
    data: clicksChartData,
    options: {
        plugins: {
            legend: {
                display: false
            },
        },



        indexAxis: 'x',
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index',
            axis: 'x'
        },
        scales: {
            y: {
                type: 'linear',
                grace: '5%',
                beginAtZero: true,
                ticks: {
                    precision: 0,
                },
            },
        }
    },
    onResize: function (myChart, size) {
        myChart.options.scales["x"].ticks.display = (size.height >= 140);
    },
    plugins: [{
        id: 'customPlugin',
        afterLayout: (chart, args, options) => {
            let gradient = chart.ctx.createLinearGradient(0, chart.chartArea.top, 0, chart.chartArea.bottom);
            gradient.addColorStop(0.5, "rgba(255, 99, 132, 0.4)");
            gradient.addColorStop(0.95, "rgba(255, 99, 132, 0)");
            chart.config.data.datasets[0].backgroundColor = gradient
        },
    }]
};

const clickChart = new Chart(clickChartElm, clickChartconfig);






// bar char vertical
const browserChartElm = document.getElementById('browsersData');

const browserChartData = {
    labels: ["Chrome", "Firefox", "Safari", "Opera", "Edge", "IE", "Others"],
    datasets: [{
        label: 'Views',
        data: [149, 104, 73, 35, 125, 103, 56],
        backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)'
        ],
        borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)'
        ],
        borderWidth: 2
    }]
};


const browserChartconfig = {
    type: 'bar',
    data: browserChartData,
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        indexAxis: 'x',
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index',
            axis: 'x'
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    },

};

const browserChart = new Chart(browserChartElm, browserChartconfig);



// doughnut chart
const refererChartElm = document.getElementById('refererData');

const refererChartData = {
    labels: [
        "rohitdotxyz.w3spaces.com",
        "cdpn.io",
        "null.jsbin.com",
        "fiddle.jshell.net",
        "l.facebook.com",
        "l.instagram.com",
        "kutt.it",
        "mail.google.com",
        "direct"
    ],
    datasets: [{
        label: 'referers',
        data: [43, 44, 57, 76, 48, 18, 67, 62, 152],
        backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)'
        ],
        borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)'
        ],
        borderWidth: 2
    }]
};


const refererChartconfig = {
    type: 'doughnut',
    data: refererChartData,
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        indexAxis: 'x',
        responsive: true,
        maintainAspectRatio: false,
    }
};

const refererChart = new Chart(refererChartElm, refererChartconfig);



// bar char horizontal
const osChartElm = document.getElementById('osData');

const osChartData = {
    labels: ["Winndows", "Macos", "Linux", "Android", "iOS", "Others"],
    datasets: [{
        label: 'Views',
        data: [116, 24, 123, 97, 182, 21],
        backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
        ],
        borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
        ],
        borderWidth: 2
    }]
};

const osChartconfig = {
    type: 'bar',
    data: osChartData,
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index',
            axis: 'y'
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    },

};

const osChart = new Chart(osChartElm, osChartconfig);




const countriesChartElm = document.getElementById("countriesData");

const countriesList = document.querySelector("#countriesList");
const countriesMap = document.querySelector("#countriesMap")
const countryToolTip = document.querySelector("#countryTooltip");

countriesMap.addEventListener("mousemove", function (e) {
    const countryPathOnMap = e.target.closest(".country__path")

    countryToolTip.style.left = e.clientX - (countryToolTip.offsetWidth / 2) + 'px';
    countryToolTip.style.top = e.clientY - (countryToolTip.offsetHeight * 1.5) + 'px';

    if (!countryPathOnMap) {
        countryToolTip.classList.add("opacity-0")
        return false
    }

    if (countryPathOnMap == "-") {
        console.log("country code is not availabble on map")
        return false
    };

    countryToolTip.innerHTML = countryPathOnMap.dataset["country"] + " : " + countryPathOnMap.dataset["visit"];
    countryToolTip.classList.remove("opacity-0")
});

countriesMap.addEventListener("mouseleave", function (e) {
    countryToolTip.classList.add("opacity-0")
})


// statsURL
const statsHeader = document.querySelector("#urlstats__url");


$(document).ready(function () {

    function showUrlStats(csrf, urlCode, by) {
        $.ajax({
            url: `${domain}/backend/urlStats.php`,
            type: "POST",
            data: {
                "xToken": csrf,
                "hunter": urlCode,
                "showBy": by
            },
            success: function (response) {
                const result = JSON.parse(response);

                switch (result.status) {
                    case "success":
                        urlStatsSuccess(result.data)
                        break;
                    case "fail":
                        urlStatsFail(result.data)
                        break;
                    case "error":
                        console.log(result.data)
                        // window.location.assign(result.message)
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

    const path = window.location.pathname.split("/")
    const custom = path[path.length - 1];
    document.title = custom;


    if (custom == "") {
        return false;
    }

    const statsCsrf = $("#urlstats__csrf")
    const byWhich = $("#urlStats__byWhich")
    showUrlStats(statsCsrf.val(), custom, byWhich.val())


    $("#urlstats").on("submmt change", function (e) {
        e.preventDefault();

        showUrlStats(statsCsrf.val(), custom, byWhich.val())
        // console.log("submit", statsCsrf, custom, byWhich)
    })


    function urlStatsSuccess(data) {
        let { hunter, visit, browser, referer, os, country } = data;

        statsHeader.innerHTML = statsHeader.innerHTML.replace("stats_for_url", hunter);

        if (!visit) {
            visit = {}
        }

        const visitLabel = Object.keys(visit);
        const visitData = Object.values(visit);
        clickChart.data.labels = visitLabel
        clickChart.data.datasets[0].data = visitData
        clickChart.update();


        if (!browser) {
            browser = {}
        }

        const browserLabel = Object.keys(browser);
        const browserData = Object.values(browser);
        browserChart.data.labels = browserLabel
        browserChart.data.datasets[0].data = browserData
        browserChart.update();

        if (!referer) {
            referer = {}
        }

        const refererLabel = Object.keys(referer);
        const refererData = Object.values(referer);
        refererChart.data.labels = refererLabel
        refererChart.data.datasets[0].data = refererData
        refererChart.update();



        if (!os) {
            os = {}
        }

        const osLabel = Object.keys(os)
        const osData = Object.values(os)
        osChart.data.labels = osLabel
        osChart.data.datasets[0].data = osData
        osChart.update();


        if (!country) {
            country = {}
        }

        // let countriesListHtml = "";
        for (const key in country) {

            const elm = document.querySelector("#" + key);
            elm.dataset.visit = country[key];
            elm.classList.add("fill-rose-500/20")
            elm.classList.add("stroke-rose-500")
            // countriesListHtml += `<li class="text-gray-700 text-sm">${elm.dataset["country"] + " : " + country[key]}</li>`
        }
        // countriesList.innerHTML = countriesListHtml;
    }


    function urlStatsFail(data) {

        let $url = (domain + "/" + custom).replace(/^(http|https):\/\/(www\.)?/, "");;

        statsHeader.innerHTML = statsHeader.innerHTML.replace("stats_for_url", $url);
        document.body.classList.add("overflow-hidden")

        errorModal.innerHTML = `<div id="content" class="max-w-[350px] bg-white flex flex-col items-center gap-y-4 bg-red-100 text-red-900 p-4 rounded-md transition-all duration-300">
            <span class="text-lg font-medium">404</span>
            <span class="text-base">Stats for ${$url} not found.</span>
            <a href="/" class= "text-lg font-medium cursor-pointer"> Home</a>
        </div>`;

        delClasses(errorModal, "opacity-0 invisible")
    }
})




