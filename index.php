<?php

require_once("./backend/config.php");

// $orderedAtDate = date("Y-m-d H:i:s",1680445709);
// $orderedTimeInSec = strtotime($orderedAtDate);
// $expiredAtDate = date("Y-m-d 11:59:59", strtotime($orderedAtDate . "+29 DAY"));
// $expiredTimeInSec = strtotime($expiredAtDate);


$page_info = array(
	'name'=> $isLoggedIn ? $user_info["user"] : "guest",
	'role'=> $isLoggedIn ? 200 : 100,
	'state' => $isLoggedIn ? 1 : 0,
	'user' => $user_info
);


if ($checkInstall != 1) {
	include('installer/installer.php');
	die();
}

// loading head tag element
include('./templates/headLoad.php');

// load page header
include('./templates/header.php');

// home page
include('./templates/home.php');

// load order page
include("./templates/checkout.php");

// load papge footer
include("./templates/footer.php");

?>