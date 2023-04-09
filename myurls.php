<?php

require_once("./backend/config.php");

$page_info = array(
	'name'=> 'myurls',
	'role'=> 200,
	'state' => 1,
	'user' => $user_info
);

if (!$isLoggedIn) {
	header("Location: $domain");
	exit();
}

// loading head tag element
include('./templates/headLoad.php');

// load page header
include('./templates/header.php');

// load order page
include("./templates/checkout.php");

// urls page
include('./templates/urlsPage.php');

// load page footer
include("./templates/footer.php");
?>

