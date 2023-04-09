<?php


    include('./countries.php');
    include('./config.php');


    $userInputs = array(
        'username' => '', 'password' => '', 'csrf' => ''
    );

    $userErrors = array(
        'username' => '', 'password' => '', 'csrf' => ''
    );


    requestCheck($_SERVER['REQUEST_METHOD'],'POST');


    if ($isLoggedIn == true) {
        $response = makeResponse("error", 500, NULL, 2);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // user
    if (isset($_POST['username'])) {
        $userInputs['username'] = trim($_POST['username']);
    }

    if (isset($_POST['password'])) {
        $userInputs['password'] = trim($_POST['password']);
    }

    if (isset($_POST["_csrf"])) {
        $userInputs['csrf'] = trim($_POST['_csrf']);
    }


    if ($userInputs['username'] == "") {
        $userErrors['username'] = "is required";
    }

    if ($userInputs['password'] == "") {
        $userErrors['password'] = "is required";
    }


    if (verifyCSRF($sesId,$userInputs['csrf']) == false) {
        $userErrors["csrf"] = "bad csrf token";
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $userInputs["username"] = $mysqli->real_escape_string($userInputs["username"]);
    $userInputs["password"] = md5($userInputs["password"].$secret);


    $selectUsernameQuery = "SELECT * FROM users WHERE user_name = ? AND user_password = ?";
    $stmt = $mysqli->prepare($selectUsernameQuery);
    $stmt->bind_param("ss", $userInputs["username"], $userInputs["password"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();


    // Make sure you type your email and password correctly. Both your password and email are case-sensitive.
    if ($row === NULL) {
        $userErrors["username"] = "invalid username or password. 1";
        $userErrors["password"] = "invalid username or password. 1";

        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if ($row["user_name"] != $userInputs["username"] || $row["user_password"] != $userInputs["password"]) {
        $userErrors["username"] = "invalid username or password. 2";
        $userErrors["password"] = "invalid username or password. 2";

        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $userId = $row["user_id"];
    $password = makeoutput($row["user_password"]);


    $createdTime = time();
    $createId = md5($createdTime.$password.SECRET);
    $uuid = md5ToUUID($createId);
    $tokenData = array('iat'=>$createdTime,'user'=>$userId,'uuid'=> $uuid);
    $utk = createAuthToken($tokenData);
    $utkExpiresIn = $createdTime + (30 * 86400);
    setAuthCookie("authToken",$utk, "/", $utkExpiresIn);


    $userData = array(
        'username' => $userInputs["username"],
    );


    $response =  makeResponse('success', 200, $userData, NULL);
    echo json_encode($response);


    $mysqli->close();
    exit();
?>