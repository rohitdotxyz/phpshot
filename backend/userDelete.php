<?php

    include('./config.php');

    $userInputs = array(
        'userId' => '', 'xtoken' => ''
    );

    $userErrors = array(
        'userId' => '', 'xtoken' => '',
        'signin' => '', 'delete' => '',
    );


    requestCheck($_SERVER["REQUEST_METHOD"],"POST");


    // completely disabled url delete
    $allowToDelete = allowByWebSetting($webSettings["canDeleteUser"]) && allowByUserAction($userActions["canDeleteUser"]);
    if (!$allowToDelete) {
        $userErrors["delete"] = "(deleting of user has been disabled temporarily.)";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $requiredFields = array(
        'userId' => true,
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
        $userErrors["signin"] = "Please signin to delete usser.";
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if (!is_numeric($userInputs["userId"]) || $userInputs["userId"] < 0) {
        $userErrors["userId"] = "invalid user id";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // escaping and hashing data
    $userInputs["userId"] = $mysqli->real_escape_string($userInputs["userId"]);

    if ($userInputs["userId"] == $user_info["id"]) {
        $userErrors["delete"] = "you can not delete yourself.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $selectUserQuery = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
    $selectUserStmt = $mysqli->prepare($selectUserQuery);
    $selectUserStmt->bind_param("i",$userInputs["userId"]);
    $selectUserStmt->execute();
    $selectUrlResult = $selectUserStmt->get_result();
    $selectUserRow = $selectUrlResult->fetch_assoc();
    $selectUserStmt->close();


    if ($selectUserRow == NULL) {
        $userErrors["id"] = "user does not exist.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $id = $selectUserRow["user_id"];
    $data = array();
    $data["id"] = $id;


    // is trying to delete url of staff
    $isRoleGreaterDbVal = isGreaterOrEqualRank($selectUserRow["user_role"]);
    if ($isRoleGreaterDbVal) {
        $userErrors["delete"] = "you have no privileges to delete staff member.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $deleteUserQuery = "DELETE users FROM users WHERE user_id = ?";
    $deleteUserStmt = $mysqli->prepare($deleteUserQuery);
    $deleteUserStmt->bind_param("i",$id);
    $deleteUserStmt->execute();
    $deleteUserStmt->close();


    $selectUrlQuery = "SELECT url_id FROM urls WHERE url_createdBy = ?";
    $selectUrlStmt = $mysqli->prepare($selectUrlQuery);
    $selectUrlStmt->bind_param("i",$id);
    $selectUrlStmt->execute();
    $selectUrlResult = $selectUrlStmt->get_result();
    $selectUrlRows = $selectUrlResult->fetch_all();
    $selectUrlStmt->close();


    if ($selectUrlRows == NULL) {
        $response = makeResponse("success", 200, $data, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $linksId = array_map(function($k) {
        return $k[0];
    }, $selectUrlRows);

    $links =  "(" . implode(",",$linksId) . ")";


    $deleteUrlQuery = "DELETE urls FROM urls WHERE url_id IN $links";
    $deleteUrlStmt = $mysqli->prepare($deleteUrlQuery);
    $deleteUrlStmt->execute();
    $deleteUrlStmt->close();

    $deleteVisitQuery = "DELETE visits FROM visits WHERE visit_urlId in $links";
    $deleteVisitStmt = $mysqli->prepare($deleteVisitQuery);
    $deleteVisitStmt->execute();
    $deleteVisitStmt->close();

    $deleteReportQuery = "DELETE reports FROM reports WHERE report_urlId in $links";
    $deleteReportStmt = $mysqli->prepare($deleteReportQuery);
    $deleteReportStmt->execute();
    $deleteReportStmt->close();



    $data["links"] = $links;

    $response = makeResponse("success", 200, $data, NULL);
    $json = json_encode($response);
    echo $json;

    $mysqli->close();
    die();

?>