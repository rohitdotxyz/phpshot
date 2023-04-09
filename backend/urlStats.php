<?php

include("./config.php");


$userInputs = array(
    'showBy' => '', 'hunter' => '', 'xToken' => ''
);


$userErrors = array(
    'hunter' => '', 'showBy' => '', 'xToken' =>  '',
    'signin' => '', 'stats' => ''
);


$requiredFields = array(
    'hunter' => true, 'showBy' => true, 'xToken' => true
);


requestCheck($_SERVER["REQUEST_METHOD"],"POST");



$allowStats = allowByWebSetting($webSettings["urlStats"]) && allowByUserAction($userActions["urlStats"]);

if (!$allowStats) {
    $userErrors["stats"] = "(stats of url has been disabled temporarily.)";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}



// keys setup
foreach ($userInputs as $key => $value) {
    if (array_key_exists($key,$_POST) && $requiredFields[$key]) {
        $userInputs[$key] = trim($_POST[$key]);
    }
}

if (!verifyCSRF($sesId,$userInputs['xToken'])) {
    $userErrors["xToken"] = "Something went wrong with xtoken.";
}

if (!$isLoggedIn) {
    $userErrors["signin"] = "Please signin to see stats.";
}

$isValidCustom = validateUrlHunter($userInputs["hunter"]);
if (is_string($isValidCustom)) {
    $userErrors["hunter"] = $isValidCustom;
}

$showBy = ["year", "month", "week", "hour"];
if (!in_array($userInputs["showBy"], $showBy)) {
    $userInputs["showBy"] = "hour";
}


if (array_filter($userErrors)) {
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$userInputs["hunter"] = $mysqli->real_escape_string($userInputs["hunter"]);
$userInputs["showBy"] = $mysqli->real_escape_string($userInputs["showBy"]);
$user_info["id"] = $mysqli->real_escape_string($user_info["id"]);
$new_date = date("Y-m-d H:i:s");


$selectUrlQuery = "SELECT * FROM urls WHERE urls.url_code = ? AND urls.url_deleted != 1 LIMIT 1";
$selectUrlStmt = $mysqli->prepare($selectUrlQuery);
$selectUrlStmt->bind_param("s",$userInputs["hunter"]);
$selectUrlStmt->execute();
$selectUrlResult = $selectUrlStmt->get_result();
$selectUrlRow = $selectUrlResult->fetch_assoc();
$selectUrlStmt->close();


if ($selectUrlRow == NULL) {
    $userErrors["hunter"] = "URL does not exist. 1";
    $response = makeResponse("fail", 404, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$isMeCheckWithDbVal = isBelongToMe($selectUrlRow["url_createdBy"]); 
$allowToCheckStats = allowByUserAction($userActions["canCheckStats"]);

if (!($isMeCheckWithDbVal || $allowToCheckStats)) {
    $userErrors["stats"] = "you have no privileges to check stats.";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$linkId = $selectUrlRow["url_id"];
$hunter = $selectUrlRow["url_code"];
$byWhich = $userInputs["showBy"];


$present = date("Y-m-d H:i:s");

// stats by click
$fromVisitQuery = "SELECT
    COUNT(CONCAT_WS(' ', DATE_FORMAT(visit_time, '%b'), DATE_FORMAT(visit_time, '%d'))) as total,
    click__column__time as label
    FROM visits
    WHERE visit_urlId = $linkId AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
    GROUP BY label
    ORDER BY visit_time ASC";


// stats by browsers
$fromBrowsersQuery = "SELECT COUNT(br_chrome) as total, 'Chrome' AS 'label'
    FROM visits
    WHERE visit_urlId = $linkId AND br_chrome = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(br_firefox) as total, 'Firefox'
    FROM visits
    WHERE visit_urlId = $linkId AND br_firefox = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(br_safari) as total, 'Safari'
    FROM visits
    WHERE visit_urlId = $linkId AND br_safari = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(br_opera) as total, 'Opera'
    FROM visits
    WHERE visit_urlId = $linkId AND br_opera = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(br_edge) as total, 'Edge'
    FROM visits
    WHERE visit_urlId = $linkId AND br_edge = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(br_ie) as total, 'IE'
    FROM visits
    WHERE visit_urlId = $linkId AND br_ie = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(br_others) as total, 'Others'
    FROM visits
    WHERE visit_urlId = $linkId AND br_others = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'";


// stats by os
$fromOsQuery = "SELECT COUNT(os_windows) as total, 'Windows' as 'label'
    FROM visits
    WHERE visit_urlId = $linkId AND os_windows = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(os_macos) as total, 'MacOs'
    FROM visits
    WHERE visit_urlId = $linkId AND os_macos = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(os_linux) as total, 'Linux'
    FROM visits
    WHERE visit_urlId = $linkId AND os_linux = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(os_android) as total, 'Android'
    FROM visits
    WHERE visit_urlId = $linkId AND os_android = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(os_ios) as total, 'iOS'
    FROM visits
    WHERE visit_urlId = $linkId AND os_ios = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
UNION
SELECT COUNT(os_others) as total, 'Others'
    FROM visits
    WHERE visit_urlId = $linkId AND os_others = 1 AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'";


// stats by referer
$fromRefererQuery = "SELECT
    COUNT(visit_referer) as total, visit_referer as label
    FROM visits
    WHERE visit_urlId = $linkId AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
    GROUP BY visit_referer";


// stats by country
$fromCountryQuery = "SELECT
    COUNT(visit_country) as total, visit_country as label
    FROM visits
    WHERE visit_urlId = $linkId AND visit_time BETWEEN DATE_SUB('$present', click__interval) AND '$present'
    GROUP BY visit_country";



switch ($byWhich) {
    case 'year':

        $fromVisitQuery = str_replace("click__column__time","CONCAT_WS(' ', DATE_FORMAT(visit_time, '%b'),DATE_FORMAT(visit_time, '%Y'))",$fromVisitQuery);
        // $fromVisitQuery = str_replace("timeColumn", "label",$fromVisitQuery);
        $fromVisitQuery = str_replace("click__interval","INTERVAL 11 MONTH",$fromVisitQuery);

        $fromBrowsersQuery = str_replace("click__interval","INTERVAL 11 MONTH",$fromBrowsersQuery);
        $fromOsQuery = str_replace("click__interval","INTERVAL 11 MONTH",$fromOsQuery);
        $fromRefererQuery = str_replace("click__interval","INTERVAL 11 MONTH",$fromRefererQuery);
        $fromCountryQuery = str_replace("click__interval","INTERVAL 11 MONTH",$fromCountryQuery);

        $old_date = date("Y-m-d H:i:s", strtotime($new_date . "-11 MONTH"));
        $dates = getBetweenDates($old_date, $new_date, "M Y", 'month'); 

    break;
    case 'month':

        $fromVisitQuery = str_replace("click__column__time", "CONCAT_WS(' ', DATE_FORMAT(visit_time, '%b'), DATE_FORMAT(visit_time, '%d'))", $fromVisitQuery);
        // $fromVisitQuery = str_replace("timeColumn", "label", $fromVisitQuery);
        $fromVisitQuery = str_replace("click__interval","INTERVAL 29 DAY",$fromVisitQuery);

        $fromBrowsersQuery = str_replace("click__interval","INTERVAL 29 DAY",$fromBrowsersQuery);
        $fromOsQuery = str_replace("click__interval","INTERVAL 29 DAY",$fromOsQuery);
        $fromRefererQuery = str_replace("click__interval","INTERVAL 29 DAY",$fromRefererQuery);
        $fromCountryQuery = str_replace("click__interval","INTERVAL 29 DAY",$fromCountryQuery);

        $old_date = date("Y-m-d H:i:s", strtotime($new_date . "-29 DAY"));
        $dates = getBetweenDates($old_date, $new_date, "M d", 'day');

    break;
    case 'week':

        $fromVisitQuery = str_replace("click__column__time", "CONCAT_WS(' ', DATE_FORMAT(visit_time, '%b'), DATE_FORMAT(visit_time, '%d'))", $fromVisitQuery);
        // $fromVisitQuery = str_replace("timeColumn", "label", $fromVisitQuery);
        $fromVisitQuery = str_replace("click__interval","INTERVAL 6 DAY",$fromVisitQuery);

        $fromBrowsersQuery = str_replace("click__interval","INTERVAL 6 DAY",$fromBrowsersQuery);
        $fromOsQuery = str_replace("click__interval","INTERVAL 6 DAY",$fromOsQuery);
        $fromRefererQuery = str_replace("click__interval","INTERVAL 6 DAY",$fromRefererQuery);
        $fromCountryQuery = str_replace("click__interval","INTERVAL 6 DAY",$fromCountryQuery);

        $old_date = date("Y-m-d H:i:s", strtotime($new_date . "-6 DAY"));
        $dates = getBetweenDates($old_date, $new_date, "M d", 'day');

    break;
    case 'hour':

        $fromVisitQuery = str_replace("click__column__time", "CONCAT_WS('',DATE_FORMAT(visit_time, '%H'), ':', '00')", $fromVisitQuery);
        // $fromVisitQuery = str_replace("timeColumn", "label", $fromVisitQuery);
        $fromVisitQuery = str_replace("click__interval","INTERVAL 23 HOUR",$fromVisitQuery);

        $fromBrowsersQuery = str_replace("click__interval","INTERVAL 23 HOUR",$fromBrowsersQuery);
        $fromOsQuery = str_replace("click__interval","INTERVAL 23 HOUR",$fromOsQuery);
        $fromRefererQuery = str_replace("click__interval","INTERVAL 23 HOUR",$fromRefererQuery);
        $fromCountryQuery = str_replace("click__interval","INTERVAL 23 HOUR",$fromCountryQuery);

        $old_date = date("Y-m-d H:i:s", strtotime($new_date . "-23 HOUR"));
        $dates = getBetweenDates($old_date, $new_date, "H:00", 'hour');

    break;
    default:

        $fromVisitQuery = str_replace("click__column__time", "CONCAT_WS('',DATE_FORMAT(visit_time, '%H'), ':', '00')", $fromVisitQuery);
        // $fromVisitQuery = str_replace("timeColumn", "label", $fromVisitQuery);
        $fromVisitQuery = str_replace("click__interval","INTERVAL 5 HOUR",$fromVisitQuery);

        $fromBrowsersQuery = str_replace("click__interval","INTERVAL 5 HOUR",$fromBrowsersQuery);
        $fromOsQuery = str_replace("click__interval","INTERVAL 5 HOUR",$fromOsQuery);
        $fromRefererQuery = str_replace("click__interval","INTERVAL 5 HOUR",$fromRefererQuery);
        $fromCountryQuery = str_replace("click__interval","INTERVAL 5 HOUR",$fromCountryQuery);

        $old_date = date("Y-m-d H:i:s", strtotime($new_date . "-5 HOUR"));
        $dates = getBetweenDates($old_date, $new_date, "H:00", 'hour');

    break;
}


    // echo "<pre>";
    // echo $fromVisitQuery;
    // echo "</pre>";
    // die();

    $fromVisitResult = $mysqli->query($fromVisitQuery);
    $fromVisitResultRow = $fromVisitResult->fetch_all(MYSQLI_ASSOC);

    $visitData = array();
    foreach ($dates as $datekey => $dateVal) {
        $visitData[$dateVal] = 0;
        foreach ($fromVisitResultRow as $visitKey => $visitVal) {
            if ($visitVal["label"] == $dateVal) {
                $visitData[$dateVal] = $visitVal["total"];
            }
        }
    }

    $fromBrowsersResult = $mysqli->query($fromBrowsersQuery);
    $fromBrowsersResultRow = $fromBrowsersResult->fetch_all(MYSQLI_ASSOC);

    $browserData = array();
    foreach ($fromBrowsersResultRow as $browserKey => $browserVal) {
        $browserData[$browserVal["label"]] = $browserVal["total"];
    }

    $fromOsResult = $mysqli->query($fromOsQuery);
    $fromOsResultRow = $fromOsResult->fetch_all(MYSQLI_ASSOC);

    $osData = array();
    foreach ($fromOsResultRow as $osKey => $osVal) {
        $osData[$osVal["label"]] = $osVal["total"];
    }

    $fromRefererResult = $mysqli->query($fromRefererQuery);
    $fromRefererResultRow = $fromRefererResult->fetch_all(MYSQLI_ASSOC);

    $refererData = array();
    foreach ($fromRefererResultRow as $refererKey => $refererVal) {
        $refererData[$refererVal["label"]] = $refererVal["total"];
    }

    $fromCountryResult = $mysqli->query($fromCountryQuery);
    $fromCountryResultRow = $fromCountryResult->fetch_all(MYSQLI_ASSOC);

    $countryData = array();
    foreach ($fromCountryResultRow as $countryKey => $countryVal) {
        $countryData[$countryVal["label"]] = $countryVal["total"];
    }


    // print_r();


    $stats = array();
    $stats["hunter"] =  parse_url($domain)["host"]. "/" . $hunter;
    $stats["visit"] = $visitData;
    $stats["browser"] = $browserData;
    $stats["referer"] = $refererData;
    $stats["os"] = $osData;
    $stats["country"] = $countryData;


    // echo "<pre>";
    // print_r($dates);
    // echo "</pre>";

    $response =  makeResponse('success', 200, $stats, NULL);
    echo json_encode($response);
    $mysqli->close();
?>