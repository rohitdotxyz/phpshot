<?php

require_once("./backend/config.php");

$page_info = array(
	'name'=> 'panel',
	'role'=> 400,
	'state' => 1,
	'user' => $user_info
);


if (!$isLoggedIn) {
	header("Location: $domain");
	exit();
}


if (!allowByUserRole($roles["mod"])) {
	header("Location: $domain");
	exit();
}


// loading head tag element
include('./templates/headLoad.php');

// load page header
include('./templates/header.php');

// admin page
include('./templates/urlsPage.php');

include('./templates/usersPage.php');

// load papge footer
include("./templates/footer.php");
?>