<?php

    include('./countries.php');
    include('./config.php');


    $userInputs = array(
        'username' => '', 'email' => '', 'password' => '', 'role' => 200, 'country' => '', 'dob' => ''
    );

    $userErrors = array(
        'username' => '', 'email' => '', 'password' => '', 'role' => '', 'country' => '', 'dob' => '', 'csrf' => ''
    );


    requestCheck($_SERVER['REQUEST_METHOD'], 'POST');


    if ($isLoggedIn == true) {
        $response = makeResponse("error", 500, NULL, 2);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    if (isset($_POST['username'])) {
        $userInputs['username'] = trim($_POST['username']);
    }

    if (isset($_POST['email'])) {
        $userInputs['email'] = trim($_POST['email']);
    }

    if (isset($_POST['password'])) {
        $userInputs['password'] = trim($_POST['password']);
    }

    if (isset($_POST['dob'])) {
        $userInputs['dob'] = trim($_POST['dob']);
    }

    if (isset($_POST['country'])) {
        $userInputs['country'] = trim($_POST['country']);
    }

    if (isset($_POST["_csrf"])) {
        $userInputs['csrf'] = trim($_POST['_csrf']);
    }


    $isValidUsername = validateSlug($userInputs['username']);
    if (is_string($isValidUsername)) {
        $userErrors['username'] = $isValidUsername;
    }

    $isValidUserEmail = validateUserEmail($userInputs['email']);
    if (is_string($isValidUserEmail)) {
        $userErrors['email'] = $isValidUserEmail;
    }

    $isValidUserPass = validateUserPass($userInputs['password']);
    if (is_string($isValidUserPass)) {
        $userErrors['password'] = $isValidUserPass;
    }

    $isValidUserCountry = validateUserCountry($userInputs['country'],$world);
    if (is_string($isValidUserCountry)) {
        $userErrors['country'] = $isValidUserCountry;
    }

    $isValidUserDob = validateUserDob($userInputs['dob']);
    if (is_string($isValidUserDob)) {
        $userErrors['dob'] = $isValidUserDob;
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
    $userInputs["email"] = $mysqli->real_escape_string($userInputs["email"]);
    $userInputs["role"] = $mysqli->real_escape_string($userInputs["role"]);
    $userInputs["password"] = md5($userInputs["password"].$secret);
    $userInputs["country"] = $mysqli->real_escape_string($userInputs["country"]);
    $userInputs["dob"] = $mysqli->real_escape_string($userInputs["dob"]);


    // isUnique
    $isUniqeUsername = doesSlugExistInDb($userInputs["username"],$mysqli);
    if ($isUniqeUsername != 1) {
        $userErrors["username"] = $isUniqeUsername;
    }

    $isUniqeEmail = doesEmailExistInDb($userInputs["email"],$mysqli);
    if ($isUniqeEmail != 1) {
        $userErrors["email"] = $isUniqeEmail;
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // Insert Data into DB
    $insertUserQuery = "INSERT INTO users (user_name, user_email, user_role, user_password, user_country, user_dob) VALUES (?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($insertUserQuery);
    $stmt->bind_param("ssisss", $userInputs["username"], $userInputs["email"], $userInputs["role"], $userInputs["password"], $userInputs["country"], $userInputs["dob"]);
    $stmt->execute();
    $userId = $stmt->insert_id;
    $stmt->close();


    // encode
    $userName = makeoutput($userInputs["username"]);
    $userPass = makeoutput($userInputs["password"]);
    $userEmail = makeoutput($userInputs["email"]);
    $userRole = makeOutput($userInputs["role"]);
    $userDob = makeoutput($userInputs["dob"]);
    $userCountry = makeoutput($userInputs["country"]);


    $createdTime = time();
    $createId = md5($createdTime.$userPass.SECRET);
    $uuid = md5ToUUID($createId);
    $tokenData = array('iat'=>$createdTime,'user'=>$userId,'uuid'=> $uuid);
    $utk = createAuthToken($tokenData);
    $utkExpiresIn = $createdTime + (30 * 86400);
    setAuthCookie("authToken",$utk, "/", $utkExpiresIn);

    // $createdTime = time();
    // $createId = md5($createdTime.$userPass.SECRET);
    // $uuid = md5ToUUID($createId);
    // $tokenData = array('iat'=>$createdTime,'user'=>$userName,'uuid'=> $uuid);
    // $utk = createAuthToken($tokenData);
    // $utkExpiresIn = $createdTime + (30 * 86400);
    // setAuthCookie("authToken",$utk, "/", $utkExpiresIn);


    $userData = array(
        'id' => $userId,
        'username' => $userName,
        'email' => $userEmail,
        'role' => $userRole,
        'country' => $userCountry,
        'dob' => $userDob,
    );


    $response =  makeResponse('success', 201, $userData, NULL);
    echo json_encode($response);


    $mysqli->close();
    exit();
?>