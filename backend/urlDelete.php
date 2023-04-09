<?php
    include('./config.php');


    $userInputs = array(
        'author' => '', 'linkId' => '', 'xtoken' => ''
    );


    $userErrors = array(
        'author' => '', 'linkId' => '', 'xtoken' => '',
        'signin' => '', 'delete' => '',
    );


    requestCheck($_SERVER["REQUEST_METHOD"],"POST");


    // completely disabled url delete
    $allowToDelete = allowByWebSetting($webSettings["urlDelete"]) && allowByUserAction($userActions["urlDelete"]);
    if (!$allowToDelete) {
        $userErrors["delete"] = "(deleting of url has been disabled temporarily.)";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // requestCheck($_SERVER["REQUEST_METHOD"], "POST");


    $requiredFields = array(
        'author' => false,
        'linkId' => true,
        'xtoken' => true,
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
        $userErrors["signin"] = "Please signin to delete url.";
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if (!is_numeric($userInputs["linkId"]) || $userInputs["linkId"] < 0) {
        $userErrors["linkId"] = "invalid url id";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // escaping and hashing data
    $userInputs["linkId"] = $mysqli->real_escape_string($userInputs["linkId"]);


    $selectColumn = "url_id, url_createdBy as user_id";
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


    $userInputs["author"] = $selectUrlRow["user_id"];
    $isMeCheckWithDbVal = isBelongToMe($userInputs["author"]);
    $allowToDeleteOther = allowByUserAction($userActions["canDeleteLink"]);


    if (!($isMeCheckWithDbVal || $allowToDeleteOther)) {
        $userErrors["linkId"] = "you have no privileges to delete url of users";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if ($userInputs["author"] == 0) {
        $selectUrlRow["user_role"] = 100;
    } else {
        $selectUserQuery = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
        $selectUserStmt = $mysqli->prepare($selectUserQuery);
        $selectUserStmt->bind_param("i",$userInputs["author"]);
        $selectUserStmt->execute();
        $selectUserResult = $selectUserStmt->get_result();
        $selectUserRow = $selectUserResult->fetch_assoc();
        $selectUserStmt->close();

        $selectUrlRow["user_role"] = $selectUserRow["user_role"];
    }


    // is trying to delete url of staff
    $isRoleGreaterDbVal = isGreaterOrEqualRank($selectUrlRow["user_role"]);
    if (!$isMeCheckWithDbVal && $isRoleGreaterDbVal) {
        $userErrors["delete"] = "you have no privileges to delete url of staff.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $data = array(
        "linkId" => $userInputs["linkId"]
    );


    if ($allowToDeleteOther) {
        $deleteUrlQuery = "DELETE urls FROM urls WHERE $whereQuery";
    } else {
        $deleteUrlQuery = "UPDATE urls SET url_deleted = 1 WHERE $whereQuery";
    }

    $deleteUrlStmt = $mysqli->prepare($deleteUrlQuery);
    $deleteUrlStmt->bind_param("i",$userInputs["linkId"]);
    $deleteUrlStmt->execute();
    $deleteUrlStmt->close();


    if ($allowToDeleteOther) {
        $deleteReportQuery = "DELETE reports FROM reports WHERE report_urlId = ?";
        $deleteReportStmt = $mysqli->prepare($deleteReportQuery);
        $deleteReportStmt->bind_param("i",$userInputs["linkId"]);
        $deleteReportStmt->execute();
        $deleteReportStmt->close();
    }


    $response = makeResponse("success", 200, $data, NULL);
    $json = json_encode($response);
    echo $json;

    $mysqli->close();
    exit();
?>