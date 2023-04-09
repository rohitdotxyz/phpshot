<?php

    $userInputs = array(
        "custom" => "", "password" => "", "csrf" => "", "country" => "", "referer" => "", "ip" => "", "agent" => ""
    );

    $userErrors = array(
        "custom" => "", "password" => "", 'csrf' => ''
    );


    require('../vendor/autoload.php');


    include('./config.php');
    include('./countries.php');


    requestCheck($_SERVER["REQUEST_METHOD"], "POST");


    $browsersList = array(
        "internet explorer" => "ie",
        "firefox" => "firefox",
        "chrome" => "chrome",
        "opera" => "opera",
        "opera mobile" => "opera",
        "safari" => "safari",
        "edge" => "edge",
    );

    $osList = array(
        "windows" => "windows",
        "linux" => "linux",
        "unix" => "linux",
        "macos" => "macos",
        "os x" => "macos",
        "android" => "android",
        "ios" => "ios"
    );

   

    // echo "<p>System</p>";
    // echo $osName;
    // echo "<p>Browser</p>";
    // echo $brName;


    if (isset($_POST['custom'])) {
        $userInputs['custom'] = trim($_POST['custom']);
    }

    if (isset($_POST['password'])) {
        $userInputs['password'] = trim($_POST['password']);
    }

    if (isset($_POST["_csrf"])) {
        $userInputs['csrf'] = trim($_POST['_csrf']);
    }

    if (isset($_POST["country"])) {
        $userInputs['country'] = trim($_POST['country']);
    }

    // if country code does not exist put US
    if (!array_key_exists($userInputs["country"],$world)) {
        $userInputs["country"]  = "US";
    }


    // if (isset($_SERVER["HTTP_REFERER"])) {
    //     $userInputs['sreferer'] = trim($_SERVER['HTTP_REFERER']);
    // }

    if (isset($_POST["referer"])) {
        $userInputs["referer"] = trim($_POST["referer"]);
    }

    if ($userInputs["referer"] == "") {
        $userInputs["referer"] = "direct";
    }

    $userInputs["referer"] = urldecode($userInputs["referer"]);
    $urlParts = parse_url($userInputs["referer"]);
    if(isset($urlParts["host"]) &&  $urlParts != "") {
        $userInputs["referer"] = preg_replace('/^www\./', '', $urlParts['host']);
    }

    $userInputs["ip"] = getIp();
    $userInputs["ip"] = implode(",",$userInputs["ip"]);

    if (isset($_SERVER["HTTP_USER_AGENT"])) {
        $userInputs["agent"] = trim($_SERVER["HTTP_USER_AGENT"]);
    }


    $userInputs["custom"] = $mysqli->real_escape_string($userInputs["custom"]);
    $userInputs["referer"] = $mysqli->real_escape_string($userInputs["referer"]);
    $userInputs["country"] = $mysqli->real_escape_string($userInputs["country"]);
    $userInputs["ip"] = $mysqli->real_escape_string($userInputs["ip"]);
    $userInputs["agent"] = $mysqli->real_escape_string($userInputs["agent"]);


    if ($userInputs["password"] != "") {
        if (!verifyCSRF($sesId,$userInputs['csrf'])) {
            $userErrors["csrf"] = "something went wrong.";
        }

        $userInputs["password"] = md5($userInputs["password"]);
    }




    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $parsedUserAgent = new WhichBrowser\Parser($_SERVER["HTTP_USER_AGENT"]);

    $osName = null ?? "others";
    $oName  = strtolower($parsedUserAgent->os->name);
    $oAlias  = strtolower($parsedUserAgent->os->alias);

    foreach ($osList as $key => $value) {
        // echo $key . " " . $value . "<br />";
        if ($key == $oName || $key == $oAlias) {
            $osName = $value;
        }
    }

    $brName = null ?? "others";
    $bName  = strtolower($parsedUserAgent->browser->name);
    $bAlias  = strtolower($parsedUserAgent->browser->alias);

    foreach ($browsersList as $key => $value) {
        // echo $key . " " . $value . "<br />";
        if ($key == $bName || $key == $bAlias) {
            $brName = $value;
        }
    }





    $selectUrlQuery = "SELECT * FROM urls WHERE url_code = ?";
    $stmt = $mysqli->prepare($selectUrlQuery);
    $stmt->bind_param("s", $userInputs["custom"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();


    if ($row === NULL) {
        $userErrors["custom"] = "short url does not exist.";
        $response = makeResponse("fail", 404, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if ($row["url_code"] != $userInputs["custom"]) {
        $userErrors["custom"] = "something went wrong with short url.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if ($row["url_password"] != "" && $userInputs["password"] == "") {
        $userErrors["password"] = "password is required to access this url.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if ($row["url_password"] != $userInputs["password"]) {
        $userErrors["password"] = "incorrect password or short url.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // database data
    $targetUrl = makeoutput($row["url_long"]);

    $userData = array(
        "url" => $targetUrl,
    );

    // build insert query
    $tableCols = array();
    $tablePara = array();
    $paraTypes = array();
    $tableVals = array();


    $tableCols["id"] = "visit_urlId";
    $tablePara["id"] = "?";
    $paraTypes["id"] = "i";
    $tableVals["id"] = $row["url_id"];

    if ($userInputs["referer"] != "") {
        $tableCols["referer"] = "visit_referer";
        $tablePara["referer"] = "?";
        $paraTypes["referer"] = "s";
        $tableVals["referer"] = $userInputs["referer"];
    }

    $tableCols["br"] = "br_$brName";
    $tablePara["br"] = "?";
    $paraTypes["br"] = "i";
    $tableVals["br"] = 1;

    $tableCols["os"] = "os_$osName";
    $tablePara["os"] = "?";
    $paraTypes["os"] = "i";
    $tableVals["os"] = 1;

    if ($userInputs["country"] != "") {
        $tableCols["country"] = "visit_country";
        $tablePara["country"] = "?";
        $paraTypes["country"] = "s";
        $tableVals["country"] = $userInputs["country"];
    }

    if ($userInputs["ip"] !=  "") {
        $tableCols["ip"] = "visit_ip";
        $tablePara["ip"] = "?";
        $paraTypes["ip"] = "s";
        $tableVals["ip"] = $userInputs["ip"];
    }

    if ($userInputs["agent"] !=  "") {
        $tableCols["agent"] = "visit_agent";
        $tablePara["agent"] = "?";
        $paraTypes["agent"] = "s";
        $tableVals["agent"] = $userInputs["agent"];
    }


    $tableCols["time"] = "visit_time";
    $tablePara["time"] = "?";
    $paraTypes["time"] = "s";
    $tableVals["time"] = date("Y-m-d H:i:s");


    $tableCols = implode(",",$tableCols);
    $tablePara = implode(",",$tablePara);
    $paraTypes = implode("", $paraTypes);
    $tableVals = array_values($tableVals);



    // echo json_encode($tableCols);
    // echo json_encode($tablePara);
    // echo json_encode($paraTypes);
    // echo json_encode($tableVals);
    // echo json_encode($userInputs);
    // die();


    // Insert Visitor
    $insertVisitQuery = "INSERT INTO visits ($tableCols) VALUES ($tablePara)";
    $insertVisitStmt = $mysqli->prepare($insertVisitQuery);
    $insertVisitStmt->bind_param($paraTypes, ...$tableVals);
    $insertVisitStmt->execute();
    $insertVisitStmt->close();


    $row["url_visits"] = $row["url_visits"] + 1;

    // Inserst Data into DB
    $updateUrlQuery = "UPDATE urls SET url_visits = ? WHERE url_id = ?";
    $updateUrlStmt = $mysqli->prepare($updateUrlQuery);
    $updateUrlStmt->bind_param("ii", $row["url_visits"],$row["url_id"]);
    $updateUrlStmt->execute();


    $response =  makeResponse('success', 200, $userData, NULL);
    echo json_encode($response);
    exit();
?>