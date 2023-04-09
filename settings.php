<?php

require_once("./backend/config.php");

$page_info = array(
	'name'=> 'setting',
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

// setting page
include('./templates/updateUser.php');

// load papge footer
include("./templates/footer.php");


?>