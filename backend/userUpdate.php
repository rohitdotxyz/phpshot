<?php

include('./countries.php');
include('./config.php');


$userInputs = array(
    'id' => '',
    'username' => '',
    'email' => '',
    'password' => '',
    'role' => '',
    'country' => '',
    'dob' => '',
    'xtoken' => ''
);

$userErrors = array(
    'id' => '',
    'username' => '',
    'email' => '',
    'password' => '',
    'role' => '',
    'country' => '',
    'dob' => '',
    'xtoken' => ''
);


// completely disabled url delete
$allowToUpdate = allowByWebSetting($webSettings["canUpdateUser"]) && allowByUserAction($userActions["canUpdateUser"]);
if (!$allowToUpdate) {
    $userErrors["update"] = "(update of user has been disabled temporarily.)";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$requiredFields = array(
    'id' => 1,
    'username' => 1,
    'email' => 1,
    'password' => 1,
    'role' => 1,
    'country' => 1,
    'dob' => 1,
    'xtoken' => 1
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
    $userErrors["signin"] = "Please signin to update user.";
}


if (array_filter($userErrors)) {
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


if (!is_numeric($userInputs["id"]) || $userInputs["id"] < 0) {
    $userErrors["id"] = "invalid user id";
}

$isValidUsername = validateSlug($userInputs['username']);
if ($userInputs['username'] && is_string($isValidUsername)) {
    $userErrors['username'] = $isValidUsername;
}

$isValidUserEmail = validateUserEmail($userInputs['email']);
if ($userInputs['email'] && is_string($isValidUserEmail)) {
    $userErrors['email'] = $isValidUserEmail;
}

if (!is_numeric($userInputs["role"]) || $userInputs["role"] < 0) {
    $userErrors["role"] = "invalid role";
}

$isValidUserPass = validateUserPass($userInputs['password']);
if ($userInputs['password'] && is_string($isValidUserPass)) {
    $userErrors['password'] = $isValidUserPass;
}

$isValidUserCountry = validateUserCountry($userInputs['country'],$world);
if ($userInputs['country'] && is_string($isValidUserCountry)) {
    $userErrors['country'] = $isValidUserCountry;
}

$isValidUserDob = validateUserDob($userInputs['dob']);
if ($userInputs['dob'] && is_string($isValidUserDob)) {
    $userErrors['dob'] = $isValidUserDob;
}


if (array_filter($userErrors)) {
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


// escape data
$userInputs['id'] = $mysqli->real_escape_string($userInputs['id']);
$userInputs['username'] = $mysqli->real_escape_string($userInputs['username']);
$userInputs['email'] = $mysqli->real_escape_string($userInputs['email']);

if ($userInputs['password'] != "") {
    $userInputs['password'] = md5($userInputs["password"].$secret);
}

$userInputs['role'] = $mysqli->real_escape_string($userInputs['role']);
$userInputs['country'] = $mysqli->real_escape_string($userInputs['country']);
$userInputs['dob'] = $mysqli->real_escape_string($userInputs['dob']);
$userInputs['xtoken'] = $mysqli->real_escape_string($userInputs['xtoken']);


$selectUserQuery = "SELECT * FROM users WHERE user_id = ? LIMIT 1";
$selectUserStmt = $mysqli->prepare($selectUserQuery);
$selectUserStmt->bind_param("i",$userInputs["id"]);
$selectUserStmt->execute();
$selectUserResult = $selectUserStmt->get_result();
$selectUserRow = $selectUserResult->fetch_assoc();
$selectUserStmt->close();


if ($selectUserRow == NULL) {
    $userErrors["id"] = "user does not exist.";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$isMeCheckWithDbVal = isBelongToMe($selectUserRow["user_id"]);

if (
    $isMeCheckWithDbVal &&
    ($userInputs["role"] < $user_info["role"] || $userInputs["role"] > $user_info["role"])
) {
    $userErrors["role"] = "you can not change your role.";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


// is trying to update staff member
$isRoleGreaterDbVal = isGreaterOrEqualRank($selectUserRow["user_role"]);
if (!$isMeCheckWithDbVal && $isRoleGreaterDbVal) {
    $userErrors["update"] = "you have no privileges to edit staff member.";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$isRoleGreaterInpVal = isGreaterOrEqualRank($userInputs["role"]);
if (!$isMeCheckWithDbVal && $isRoleGreaterInpVal) {
    $userErrors["role"] = "can not assign role equal to you.";
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}



// build query
$cols = array();
$cast = array();
$vals = array();
$data = array();


if ($userInputs["username"] && $userInputs["username"] != $selectUserRow["user_name"]) {
    $isUniqeUsername = doesSlugExistInDb($userInputs["username"],$mysqli);
    if ($isUniqeUsername != 1) {
        $userErrors["username"] = $isUniqeUsername;
    }

    array_push($cols, "user_name = ?");
    array_push($cast, "s");
    array_push($vals, $userInputs["username"]);
    $data["username"] = $userInputs["username"];
}


if ($userInputs["email"] && $userInputs["email"] != $selectUserRow["user_email"]) {
    $isUniqeEmail = doesEmailExistInDb($userInputs["email"],$mysqli);
    if ($isUniqeEmail != 1) {
        $userErrors["email"] = $isUniqeEmail;
    }

    array_push($cols, "user_email = ?");
    array_push($cast, "s");
    array_push($vals, $userInputs["email"]);
    $data["email"] = $userInputs["email"];
}


if ($userInputs["role"] && $userInputs["role"] != $selectUserRow["user_role"]) {
    array_push($cols, "user_role = ?");
    array_push($cast, "s");
    array_push($vals, $userInputs["role"]);
    $data["role"] = $userInputs["role"];
}


if ($userInputs["password"] && $userInputs["password"] != $selectUserRow["user_password"]) {
    array_push($cols, "user_password = ?");
    array_push($cast, "s");
    array_push($vals, $userInputs["password"]);
}


if ($userInputs["country"] && $userInputs["country"] != $selectUserRow["user_country"]) {
    array_push($cols, "user_country = ?");
    array_push($cast, "s");
    array_push($vals, $userInputs["country"]);
    $data["country"] = $userInputs["country"];
}


if ($userInputs["dob"] && $userInputs["dob"] != $selectUserRow["user_dob"]) {
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
$id = $userInputs["id"];
array_push($cast, "i");
array_push($vals,$id);

$cast = implode("",$cast);


$updateUserQuery = "UPDATE users SET $cols WHERE $whereQuery";
$updateUserStmt = $mysqli->prepare($updateUserQuery);
$updateUserStmt->bind_param($cast, ...$vals);
$updateUserStmt->execute();
$updateUserStmt->close();


$response = makeResponse("success", 200, $userInputs, $updateUserQuery);
$json = json_encode($response);
echo $json;
return false;



?>