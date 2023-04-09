<?php

include('./config.php');

$userInputs = array(
    'oldpass' => '',
    'newpass' => '',
    'xtoken' => ''
);


$userErrors = array(
    'oldpass' => '',
    'newpass' => '',
    'xtoken' => '',
    'signin' => '',
    'update' => ''
);


$requiredInputs = array(
    'oldpass' => 1,
    'newpass' => 1,
    'xtoken' => 1
);


requestCheck($_SERVER["REQUEST_METHOD"],"POST");


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
    $userErrors["signin"] = "Please signin to update password.";
}


if (array_filter($userErrors)) {
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$isValidOldPass = validateUserPass($userInputs['oldpass']);
if (is_string($isValidOldPass)) {
    $userErrors['oldpass'] = $isValidOldPass;
}

$isValidNewPass = validateUserPass($userInputs['newpass']);
if (is_string($isValidNewPass)) {
    $userErrors['newpass'] = $isValidNewPass;
}


if (array_filter($userErrors)) {
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$userInputs["oldpass"] = md5($userInputs["oldpass"].$secret);
$userInputs["newpass"] = md5($userInputs["newpass"].$secret);


if ($userInputs["oldpass"] != $user_info["password"]) {
    $userErrors["oldpass"] = "invalid password.";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}

if ($userInputs["oldpass"] == $userInputs["newpass"]) {
    $userErrors["newpass"] = "please choose new password from previous one.";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$updateUserPassQuery = "UPDATE users SET user_password = ? WHERE user_id = ?";
$updateUserPassStmt = $mysqli->prepare($updateUserPassQuery);
$updateUserPassStmt->bind_param("si", $userInputs["newpass"], $user_info["id"]);
$updateUserPassStmt->execute();
$updateUserPassStmt->close();

$data = array();
$data["password"] = 1;

$response = makeResponse("success", 200, $data, NULL);
$json = json_encode($response);
echo $json;
$mysqli->close();
exit();



?>