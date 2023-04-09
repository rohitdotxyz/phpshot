<?php

    include('./config.php');

    $userInputs = array(
        'csrf' => ''
    );

    $userErrors = array(
        'csrf' => ''
    );


    requestCheck($_SERVER['REQUEST_METHOD'],'POST');


    if (isset($_POST["_csrf"])) {
        $userInputs['csrf'] = trim($_POST['_csrf']);
    }


    // kill session
    if (verifyCSRF($sesId,$userInputs['csrf']) == false) {
        $userErrors["csrf"] = "bad csrf token";
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    unsetAuthCookie("authToken");
    unsetAuthCookie("PHPSESSID");


    $userData = array(
        "signout" => 1
    );


    $response =  makeResponse('success', 200, $userData, NULL);
    echo json_encode($response);

?>