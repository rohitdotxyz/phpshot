<?php


    include('./config.php');


    $userInputs = array(
        'linkId' => '',
        'target' => '',
        'hunter' => '',
        'secret' => '',
        'xtoken' => ''
    );


    $userErrors = array(
        'linkId' => '',
        'target' => '',
        'hunter' => '',
        'secret' => '',
        'xtoken' =>  '',
        'signin' => '',
        'update' => '',
    );


    requestCheck($_SERVER["REQUEST_METHOD"],"POST");


    $allowUpdate = allowByWebSetting($webSettings["urlUpdate"]) && allowByUserAction($userActions["urlUpdate"]);
    $allowTarget = allowByWebSetting($webSettings["urlUpdateTarget"]) && allowByUserAction($userActions["urlUpdateTarget"]);
    $allowHunter = allowByWebSetting($webSettings["urlUpdateHunter"]) && allowByUserAction($userActions["urlUpdateHunter"]);
    $allowSecret = allowByWebSetting($webSettings["urlUpdateSecret"]) && allowByUserAction($userActions["urlUpdateSecret"]);


    $requiredFields = array(
        'linkId' => 1,
        'target' => $allowTarget,
        'hunter' => $allowHunter,
        'secret' => $allowSecret,
        'xtoken' => 1
    );

    // completely disabled url update
    if (!$allowUpdate) {
        $userErrors["update"] = "(editing of url has been disabled temporarily.)";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // requestCheck($_SERVER["REQUEST_METHOD"], "POST");


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
        $userErrors["signin"] = "Please signin to update url.";
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if (!is_numeric($userInputs["linkId"]) || $userInputs["linkId"] < 0) {
        $userErrors["linkId"] = "invalid url id";
    }

    $isValidTarget = validateUrlTarget($userInputs["target"]);
    if ($allowTarget && $userInputs["target"] && is_string($isValidTarget)) {
        $userErrors["target"] = $isValidTarget;
    }

    $isValidCustom = validateUrlHunter($userInputs["hunter"]);
    if ($allowHunter && $userInputs["hunter"] && is_string($isValidCustom)) {
        $userErrors["hunter"] = $isValidCustom;
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


    // escaping and hashing data
    $userInputs["target"] = $mysqli->real_escape_string($userInputs["target"]);
    $userInputs["hunter"] = $mysqli->real_escape_string($userInputs["hunter"]);
    $userInputs["linkId"] = $mysqli->real_escape_string($userInputs["linkId"]);

    if ($userInputs["secret"] != "") {
        $userInputs["secret"] = md5($userInputs["secret"]);
    }


    $selectColumn = "url_id, url_long, url_code, url_password, url_createdBy as user_id";
    $whereQuery = "url_id = ?";


    // show or hide deleted url according to allowed role
    if (allowByUserAction($userActions["deleted"])) {
        $whereQuery = $whereQuery . " AND url_deleted >= 0";
    } else {
        $whereQuery = $whereQuery . " AND url_deleted = 0";
    }


    $selectUrlQuery = "SELECT $selectColumn FROM urls WHERE $whereQuery LIMIT 1";
    $selectUrlStmt = $mysqli->prepare($selectUrlQuery);
    $selectUrlStmt->bind_param("i",$userInputs["linkId"]);
    $selectUrlStmt->execute();
    $selectUrlResult = $selectUrlStmt->get_result();
    $selectUrlRow = $selectUrlResult->fetch_assoc();
    $selectUrlStmt->close();


    if ($selectUrlRow == NULL) {
        $userErrors["linkId"] = "url does not exist.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $urlAuthor = $selectUrlRow["user_id"];
    $isMeCheckWithDbVal = isBelongToMe($urlAuthor);
    $allowToUpdateOther = allowByUserAction($userActions["canUpdateLink"]);


    if (!($isMeCheckWithDbVal || $allowToUpdateOther)) {
        $userErrors["linkId"] = "you have no privileges to edit url of users";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if ($urlAuthor == 0) {
        $selectUrlRow["user_role"] = 100;
    } else {
        $selectUserQuery = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
        $selectUserStmt = $mysqli->prepare($selectUserQuery);
        $selectUserStmt->bind_param("i",$urlAuthor);
        $selectUserStmt->execute();
        $selectUserResult = $selectUserStmt->get_result();
        $selectUserRow = $selectUserResult->fetch_assoc();
        $selectUserStmt->close();

        $selectUrlRow["user_role"] = $selectUserRow["user_role"];
    }


    // is trying to update url of staff
    $isRoleGreaterDbVal = isGreaterOrEqualRank($selectUrlRow["user_role"]);
    if (!$isMeCheckWithDbVal && $isRoleGreaterDbVal) {
        $userErrors["update"] = "you have no privileges to edit url of staff.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $cols = array();
    $cast = array();
    $vals = array();
    $data = array();

    if ($allowTarget && $userInputs["target"] && $userInputs["target"] != $selectUrlRow["url_long"]) {
        array_push($cols, "url_long = ?");
        array_push($cast, "s");
        array_push($vals, $userInputs["target"]);
        $data["targetUrl"] = $userInputs["target"];
    }

    if ($allowHunter && $userInputs["hunter"] && $userInputs["hunter"] != $selectUrlRow["url_code"]) {
        $isUrlCodeUnique = doesUrlCodeExist($userInputs["hunter"]);
        if ($isUrlCodeUnique != 1) {
            $userErrors["hunter"] = $isUrlCodeUnique;
        }

        array_push($cols, "url_code = ?");
        array_push($cast, "s");
        array_push($vals, $userInputs["hunter"]);
        $data["hunterCode"] = $userInputs["hunter"];
    }

    if ($allowSecret && $userInputs["secret"] && $userInputs["secret"] != $selectUrlRow["url_password"]) {
        array_push($cols, "url_password = ?");
        array_push($cast, "s");
        array_push($vals, $userInputs["secret"]);
        $data["secret"] = 1;
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // fake update if all values empty
    if (!array_filter($vals)) {
        $response = makeResponse("success", 304, $userInputs, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // actual update start
    array_push($cast, "i");
    array_push($vals, $userInputs["linkId"]);


    $cols = implode(",", $cols);
    $cast = implode("", $cast);
    // $vals = implode(",", $vals);


    $updateUrlQuery = "UPDATE urls SET $cols WHERE $whereQuery";
    $updateUrlStmt = $mysqli->prepare($updateUrlQuery);
    $updateUrlStmt->bind_param($cast, ...$vals);
    $updateUrlStmt->execute();
    $updateUrlStmt->close();


    $response = makeResponse("success", 200, $data, NULL);
    $json = json_encode($response);
    echo $json;
    exit();
?>