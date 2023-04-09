<?php

    include('./config.php');


    $userInputs = array(
        "target" => "",
        "hunter" => "",
        "secret" => "",
        "xtoken" => "",
    );

    $userErrors = array(
        "target" => "",
        "hunter" => "",
        "secret" => "",
        "xtoken" => "",
        "shorturl" => "",
        "signin" => "",
    );


    requestCheck($_SERVER["REQUEST_METHOD"], "POST");


    $allowTarget = allowByWebSetting($webSettings["urlTarget"]) && allowByUserAction($userActions["urlTarget"]);
    $allowHunter = allowByWebSetting($webSettings["urlHunter"]) && allowByUserAction($userActions["urlHunter"]);
    $allowSecret = allowByWebSetting($webSettings["urlSecret"]) && allowByUserAction($userActions["urlSecret"]);

    $requiredInputs = array(
        "target" => 1,
        "hunter" => $allowHunter,
        "secret" => $allowSecret,
        "xtoken" => 1,
    );

    // complete disabled 
    if (!$allowTarget) {
        $userErrors["shorturl"] = "(shortening of url has been disabled temporarily.)";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // keys setup
    foreach ($userInputs as $key => $value) {
        if (array_key_exists($key,$_POST) && $requiredInputs[$key]) {
            $userInputs[$key] = trim($_POST[$key]);
        }
    }


    if (!verifyCSRF($sesId,$userInputs['xtoken'])) {
        $userErrors["xtoken"] = "Something went wrong with xtoken.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // url validation
    $isValidTarget = validateUrlTarget($userInputs["target"]);
    if (is_string($isValidTarget)) {
        $userErrors["target"] = $isValidTarget;
    }

    $isValidHunter = validateUrlHunter($userInputs["hunter"]);
    if ($allowHunter && $userInputs["hunter"] && is_string($isValidHunter)) {
        $userErrors["hunter"] = $isValidHunter;
    }

    $isValidSecret = validateUrlSecret($userInputs["secret"]);
    if ($allowSecret && $userInputs["secret"] && is_string($isValidSecret)) {
        $userErrors["secret"] = $isValidSecret;
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // escaping data
    $userInputs["target"] = $mysqli->real_escape_string($userInputs["target"]);
    $userInputs["hunter"] = $mysqli->real_escape_string($userInputs["hunter"]);


    if ($userInputs["secret"] != "") {
        $userInputs["secret"] = md5($userInputs["secret"]);
    }

    if ($userInputs["hunter"] != "") {
        $isUrlCodeUnique = doesUrlCodeExist($userInputs["hunter"]);
        if ($isUrlCodeUnique != 1) {
            $userErrors["hunter"] = $isUrlCodeUnique;
        }
    } else {
        $userInputs["hunter"] = generateUrlCode();
    }


    // simple error check
    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // build insert query
    $tableCols = array();
    $tablePara = array();
    $paraTypes = array();
    $tableVals = array();


    array_push($tableCols, "url_long");
    array_push($tablePara, "?");
    array_push($paraTypes, "s");
    array_push($tableVals, $userInputs["target"]);

    array_push($tableCols, "url_code");
    array_push($tablePara, "?");
    array_push($paraTypes, "s");
    array_push($tableVals, $userInputs["hunter"]);

    if ($userInputs["secret"]) {
        array_push($tableCols, "url_password");
        array_push($tablePara, "?");
        array_push($paraTypes, "s");
        array_push($tableVals, $userInputs["secret"]);
    }

    array_push($tableCols, "url_createdBy");
    array_push($tablePara, "?");
    array_push($paraTypes, "i");
    array_push($tableVals, $user_info["id"]);


    $tableCols = implode(",",$tableCols);
    $tablePara = implode(",",$tablePara);
    $paraTypes = implode("", $paraTypes);


    $insertUrlQuery = "INSERT INTO urls ($tableCols) VALUES ($tablePara)";
    $insertUrlstmt = $mysqli->prepare($insertUrlQuery);
    $insertUrlstmt->bind_param($paraTypes, ...$tableVals);
    $insertUrlstmt->execute();


    // encode
    // database data
    $linkId = $insertUrlstmt->insert_id;
    $shortLink = makeOutput($domain. "/".$userInputs["hunter"]);


    $data = array(
        "url" => $shortLink,
        "urlId" => $linkId,
    );


    $response = makeResponse("success", 201, $data, NULL);
    $json = json_encode($response);
    echo $json;


    $insertUrlstmt->close();
    $mysqli->close();
    die();
?>
