<?php

ini_set("session.cookie_httponly", "1");

require("database.php");
require("variables.php");

$checkInstall = 1;

$isLoggedIn = 0;

$user_info = array(
	'id' => 0,
	'user' => "guest",
	'email' => "",
	'role' => 100,
	'country' => ""
);

if (INSTALLED != 1) {
	$checkInstall = 2;
	return false;
}

$mysqli = @new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);
if ($mysqli->connect_error || $mysqli->connect_errno) {
	$checkInstall = 3;
	return false;
}

session_start();
require("functions.php");
require("settingss.php");



$sesId = session_id();
$csrf = createCSRF($sesId);

date_default_timezone_set(TZONE);

if (isset($_COOKIE['authToken']) && $_COOKIE['authToken'] != "") {
	$authToken = $_COOKIE['authToken'];
	$decodedAuthToken = decodeAuthToken($authToken);
	$isValidAuthToken = verifyAuthToken($decodedAuthToken['head'],$decodedAuthToken['data'],$decodedAuthToken['sign']);


	if ($isValidAuthToken == false) {
		// unsetAuthCookie('authToken');
		return false;
	}
	

	$decodedTokenInfo = tokenData(false,$decodedAuthToken['data']);
	$decodedTokenPayload = $decodedTokenInfo['dataPayload'];

	$loggedInUser = $decodedTokenPayload["user"];
	// print_r($decodedTokenPayload);


    $selectUsernameQuery = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $mysqli->prepare($selectUsernameQuery);
    $stmt->bind_param("s", $loggedInUser);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();


	if ($row === NULL) {
        return false;
	}

    if ($row["user_id"] != $loggedInUser) {
        return false;
	}


	$isLoggedIn = 1;


	$user_info['id'] = $row["user_id"];
	$user_info['user'] = $row["user_name"];
	$user_info['username'] = $row["user_name"];
	$user_info['email'] = $row["user_email"];
	$user_info['password'] = $row["user_password"];
	$user_info['role'] = $row["user_role"];
	$user_info['country'] = $row["user_country"];
	$user_info['dob'] = $row["user_dob"];
	$user_info["expAt"] = $row["expiresAt"];


	if ($user_info["expAt"] && time() > $user_info["expAt"]) {
		$user_info['role'] = 200;

		$updateRoleQuery = "UPDATE users SET user_role = 200, expiresAt = '' WHERE user_id = '{$user_info["id"]}'";
		$mysqli->query($updateRoleQuery);
	}
}


?>