<?php

    include('./config.php');
    include('./countries.php');


    $userInputs = array(
        'username' => '',
        'email' => '',
        'country' => '',
        'dob' => '',
        'password' => '',
        'xtoken' => '',
    );


    $userErrors = array(
        'username' => '',
        'email' => '',
        'country' => '',
        'dob' => '',
        'password' => '',
        'xtoken' => '',
        'signin' => '',
        'update' => ''
    );


    requestCheck($_SERVER["REQUEST_METHOD"],"POST");


    $allowUsername =  allowByWebSetting($webSettings["userUpdateUsername"]) && allowByUserAction($userActions["userUpdateUsername"]);
    $allowEmail =  allowByWebSetting($webSettings["userUpdateEmail"]) && allowByUserAction($userActions["userUpdateEmail"]);
    $allowCountry =  allowByWebSetting($webSettings["userUpdateCountry"]) && allowByUserAction($userActions["userUpdateCountry"]);
    $allowDob =  allowByWebSetting($webSettings["userUpdateDob"]) && allowByUserAction($userActions["userUpdateDob"]);


    $requiredInputs = array(
        'username' => $allowUsername,
        'email' => $allowEmail,
        'country' => $allowCountry,
        'dob' => $allowDob,
        'password' => 1,
        'xtoken' => 1,
    );


    // keys setup
    foreach ($userInputs as $key => $value) {
        if (array_key_exists($key,$_POST) && $requiredInputs[$key]) {
            $userInputs[$key] = trim($_POST[$key]);
        }
    }


    if (!verifyCSRF($sesId,$userInputs['xtoken'])) {
        $userErrors["xtoken"] = "Something went wrong with xtoken.";
    }

    if (!$isLoggedIn) {
        $userErrors["signin"] = "Please signin to update profile.";
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $isValidUsername = validateSlug($userInputs['username']);
    if ($allowUsername && $userInputs["username"] && is_string($isValidUsername)) {
        $userErrors['username'] = $isValidUsername;
    }

    $isValidUserEmail = validateUserEmail($userInputs['email']);
    if ($allowEmail && $userInputs["email"] && is_string($isValidUserEmail)) {
        $userErrors['email'] = $isValidUserEmail;
    }

    $isValidUserPass = validateUserPass($userInputs['password']);
    if (is_string($isValidUserPass)) {
        $userErrors['password'] = $isValidUserPass;
    }

    $isValidUserCountry = validateUserCountry($userInputs['country'],$world);
    if ($allowCountry && $userInputs["country"] && is_string($isValidUserCountry)) {
        $userErrors['country'] = $isValidUserCountry;
    }

    $isValidUserDob = validateUserDob($userInputs['dob']);
    if ($allowDob && $userInputs["dob"] && is_string($isValidUserDob)) {
        $userErrors['dob'] = $isValidUserDob;
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $userInputs["username"] = $mysqli->real_escape_string($userInputs["username"]);
    $userInputs["email"] = $mysqli->real_escape_string($userInputs["email"]);
    $userInputs["country"] = $mysqli->real_escape_string($userInputs["country"]);
    $userInputs["dob"] = $mysqli->real_escape_string($userInputs["dob"]);


    $cols = array();
    $cast = array();
    $vals = array();
    $data = array();


    if ($allowUsername && $userInputs["username"] && $userInputs["username"] != $user_info["username"]) {
        $isUniqeUsername = doesSlugExistInDb($userInputs["username"],$mysqli);
        if ($isUniqeUsername != 1) {
            $userErrors["username"] = $isUniqeUsername;
        }

        array_push($cols, "user_name = ?");
        array_push($cast, "s");
        array_push($vals, $userInputs["username"]);
        $data["username"] = $userInputs["username"];
    }

    if ($allowEmail && $userInputs["email"] && $userInputs["email"] != $user_info["email"]) {
        $isUniqeEmail = doesEmailExistInDb($userInputs["email"],$mysqli);
        if ($isUniqeEmail != 1) {
            $userErrors["email"] = $isUniqeEmail;
        }

        array_push($cols, "user_email = ?");
        array_push($cast, "s");
        array_push($vals, $userInputs["email"]);
        $data["email"] = $userInputs["email"];
    }

    if ($allowCountry && $userInputs["country"] && $userInputs["country"] != $user_info["country"]) {
        array_push($cols, "user_country = ?");
        array_push($cast, "s");
        array_push($vals, $userInputs["country"]);
        $data["country"] = $userInputs["country"];
    }

    if ($allowDob && $userInputs["dob"] && $userInputs["dob"] != $user_info["dob"]) {
        array_push($cols, "user_dob = ?");
        array_push($cast, "s");
        array_push($vals, $userInputs["dob"]);
        $data["dob"] = $userInputs["dob"];
    }


    if (array_filter($userErrors)) {
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    // fake update
    if (!array_filter($vals)) {
        $response = makeResponse("success", 304, $userInputs, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $cols = implode(",", $cols);
    $whereQuery = "user_id = ?";

    // where query param and cast
    $id = $user_info["id"];
    array_push($cast, "i");
    array_push($vals,$id);

    $cast = implode("",$cast);


    $password = md5($userInputs["password"].$secret);
    if ($password != $user_info["password"]) {
        $userErrors["password"] = "invalid password.";
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
        return false;
    }


    $updateUserQuery = "UPDATE users SET $cols WHERE $whereQuery";
    $updateUserStmt = $mysqli->prepare($updateUserQuery);
    $updateUserStmt->bind_param($cast, ...$vals);
    $updateUserStmt->execute();
    $updateUserStmt->close();


    $response = makeResponse("success", 200, $data, NULL);
    $json = json_encode($response);
    echo $json;


    $mysqli->close();
    die();
?>