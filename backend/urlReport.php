<?php


    include('./config.php');

    $userInputs = array(
        'reportUrl' => '',
        'reportMsg' => '',
        'xtoken' => ''
    );

    $userErrors = array(
        'reportUrl' => '',
        'reportMsg' => '',
        'xtoken' => '',
        'signin' => '',
        'report' => ''
    );


    requestCheck($_SERVER["REQUEST_METHOD"],"POST");


    // complete disabled url update
    $allowReport = allowByWebSetting($webSettings["urlReport"]) && allowByUserAction($userActions["urlReport"]);
    if (!$allowReport) {
        $userErrors["report"] = "(reporting of url has been disabled temporarily.)";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $requiredFields = array(
        "reportUrl" => $allowReport,
        'reportMsg' => true,
        'xtoken' => true
    );


    // keys setup
    foreach ($userInputs as $key => $value) {
        if (array_key_exists($key,$_POST) && $requiredFields[$key]) {
            $userInputs[$key] = trim($_POST[$key]);
        }
    }

    if (!verifyCSRF($sesId,$userInputs['xtoken'])) {
        $userErrors["xtoken"] = "Something went wrong with xtoken.";
    }

    if (!$isLoggedIn) {
        $userErrors["signin"] = "Please signin to report url.";
    }

    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // report url starts with $domain;.
    $isDomainBelogToUs = isUrlStartsWithOurDomain($domain,$userInputs["reportUrl"]);
    if (!$isDomainBelogToUs) {
        $userErrors["reportUrl"] = "url must starts with $domain.";
        $response = makeResponse("fail", 400, $userErrors, $isDomainBelogToUs);
        $json = json_encode($response);
        echo $json;
        return false;
    }

    $webDomain = $domain . "/";
    $hunterCode = str_replace($webDomain, '', $userInputs["reportUrl"]);

    if ($hunterCode == "" || $hunterCode == $domain || $hunterCode == $webDomain) {
        $userErrors["reportUrl"] = "please check url once again.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }

    $isValidCustom = validateUrlHunter($hunterCode);
    if (is_string($isValidCustom)) {
        $userErrors["reportUrl"] = $hunterCode . " " . $isValidCustom;
    }

    $isValidMsg = validateReportMsg($userInputs["reportMsg"]);
    if (is_string($isValidMsg)) {
        $userErrors["reportMsg"] = $isValidMsg;
    }

    $userInputs["reportUrl"] = $hunterCode;
    $userInputs["reportMsg"] = makeOutput($userInputs["reportMsg"]);

    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULl);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // escaping and hashing data
    $userInputs["reportUrl"] = $mysqli->real_escape_string($userInputs["reportUrl"]);
    $userInputs["reportMsg"] = $mysqli->real_escape_string($userInputs["reportMsg"]);


    $selectUrlQuery = "SELECT * FROM urls
    WHERE urls.url_code = ? AND urls.url_createdBy != ? AND urls.url_deleted = 0  LIMIT 1";

    $selectUrlStmt = $mysqli->prepare($selectUrlQuery);
	$selectUrlStmt->bind_param("si",$userInputs["reportUrl"],$user_info["id"]);
    $selectUrlStmt->execute();
    $selectUrlResult = $selectUrlStmt->get_result();
    $selectUrlRow = $selectUrlResult->fetch_assoc();
    $selectUrlStmt->close();

    if ($selectUrlRow == NULL) {
        $userErrors["reportUrl"] = "url does not exist.";
        $response = makeResponse("fail", 404, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $userId = $user_info["id"];
    $urlId = $selectUrlRow["url_id"];
    $urlCode = $selectUrlRow["url_code"];
    $urlVisits = $selectUrlRow["url_visits"];
    $urlReports = $selectUrlRow["url_reports"];


    if ($urlVisits == 0) {
        $userErrors["reportUrl"] = "url should be at least visited.";
        $response = makeResponse("fail", 404, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }

    $urlReports = $urlReports + 1;
    $reportProb = $urlReports/$urlVisits;
    $reportPerc = $reportProb * 100;
    $reportRound = floor($reportPerc);


    if ($urlReports > $urlVisits) {
        $userErrors["reportUrl"] = "url should be at least visited.";
        $response = makeResponse("fail", 404, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // 1 user id can report 1 url id
    // does user already reported that link
    $selectReportUrlQuery = "SELECT COUNT(reports.report_userId) as urlReportCount FROM reports
    WHERE reports.report_userId = ? AND reports.report_urlId = ?";

    $selectReportUrlStmt = $mysqli->prepare($selectReportUrlQuery);
    $selectReportUrlStmt->bind_param("ii",$userId,$urlId);
    $selectReportUrlStmt->execute();
    $selectReportUrlResult = $selectReportUrlStmt->get_result();
    $selectReportUrlRow = $selectReportUrlResult->fetch_assoc();
    $selectReportUrlStmt->close();

    $reportCountOnLinkByUser = $selectReportUrlRow["urlReportCount"];

    if ($reportCountOnLinkByUser >= 1) {
        $userErrors["reportUrl"] = "You have been already reported $domain/$urlCode.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $userData = array();
    $userData["reportUrl"] = $domain."/".$urlCode;
    $userData["reportMsg"] = $userInputs["reportMsg"];


    $insertReportQuery = "INSERT INTO reports (report_userId, report_urlId, report_comment)
    VALUES (?,?,?)";

    $insertReportStmt = $mysqli->prepare($insertReportQuery);
	$insertReportStmt->bind_param("iis",$userId,$urlId,$userInputs["reportMsg"]);
    $insertReportStmt->execute();
    $reportId = $insertReportStmt->insert_id;
    $insertReportStmt->close();

    if ($reportId <= 0) {
        $userErrors["report"] = "something went wrong.";
        $response = makeResponse("fail", 500, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $userData["reportId"] = $reportId;

    // is eligible to delete then delete it
    if ($selectUrlRow["url_visits"] >= 100 && $reportRound >= 75) {
        $updateReportCountQuery = "UPDATE urls SET url_reports = ?, urls.url_deleted = 1 WHERE urls.url_id = ?";
        $updateReportCountStmt = $mysqli->prepare($updateReportCountQuery);
        $updateReportCountStmt->bind_param("ii",$urlReports,$urlId);
        $updateReportCountStmt->execute();
        $updateReportCountStmt->close();

        $response = makeResponse("success", 200, $userData, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $updateReportCountQuery = "UPDATE urls SET url_reports = ? WHERE url_id = ?";
    $updateReportCountStmt = $mysqli->prepare($updateReportCountQuery);
	$updateReportCountStmt->bind_param("ii",$urlReports,$urlId);
    $updateReportCountStmt->execute();
    $updateReportCountStmt->close();


    $response = makeResponse("success", 200, $userData, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
?>