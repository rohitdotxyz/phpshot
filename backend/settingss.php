<?php

// website setting
$webSettings = array();
$webSettings["title"] = "PhpShort";
$webSettings["description"] = "your website description";
$webSettings["keywords"] = "comma sepearated keywords";

$webSettings["stripeSecret"] = "your stripe test key";

// enable 1 or disable 0
$webSettings["signup"] = 1;
$webSettings["signin"] = 1;

// shorturl
$webSettings["urlTarget"] = 1;
$webSettings["urlHunter"] = 1;
$webSettings["urlSecret"] = 1;


// search url
$webSettings["urlSearch"] = 1;
$webSettings["urlSort"] = 1;
$webSettings["urlOrder"] = 1;



// pagination
$webSettings["hunterurl"] = 1;
$webSettings["views"] = 1;
$webSettings["createdOn"] = 1;
$webSettings["targeturl"] = 1;

// total reports count
$webSettings["report"] = 1;


// updated date + update action + update form
$webSettings["urlUpdate"] = 1;
$webSettings["urlUpdateTarget"] = 1;
$webSettings["urlUpdateHunter"] = 1;
$webSettings["urlUpdateSecret"] = 1;

// protected action
$webSettings["protected"] = 1;

// delete url
$webSettings["urlDelete"] = 1;

// stats action + stats url data
$webSettings["urlStats"] = 1;

$webSettings["createdBy"] = 1;

// show deleted url
$webSettings["deleted"] = 1;

// reporturl
$webSettings["urlReport"] = 1;
$webSettings["urlMsgReport"] = 1;


// update user
$webSettings["userUpdate"] = 1;
$webSettings["userUpdateUsername"] = 1;
$webSettings["userUpdateEmail"] = 1;
$webSettings["userUpdateCountry"] = 1;
$webSettings["userUpdateDob"] = 1;



// moderation
// can update or delete other users link as well
$webSettings["canUpdateLink"] = 1;
$webSettings["canDeleteLink"] = 1;
$webSettings["canCheckStats"] = 1;

$webSettings["canUpdateUser"] = 1;
$webSettings["canDeleteUser"] = 1;


// user ranks
$roles = array();

// staff
$roles["admin"] = 500;
$roles["mod"] = 400;

// consumer
$roles["vip"] = 300;
$roles["user"] = 200;
$roles["guest"] = 100;


// actions
// enable actions for role base
$userActions = array();

// shorturl
$userActions["urlTarget"] = 100;
$userActions["urlHunter"] = 300;
$userActions["urlSecret"] = 300;


// search url
$userActions["urlSearch"] = 100;
$userActions["urlSort"] = 200;
$userActions["urlOrder"] = 200;


// pagination url
$userActions["hunterurl"] = 100;
$userActions["views"] = 200;
$userActions["createdOn"] = 200;
$userActions["targeturl"] = 200;

// total reports count
$userActions["report"] = 200;

/*
    urlUpdate and its sub component can not be more than the value of canUpdateLink
    same with urlDelete, canDeleteLink, urlStats, canCheckStats
*/

// updated date + update action + update form
$userActions["urlUpdate"] = 300;
$userActions["urlUpdateTarget"] = 300;
$userActions["urlUpdateHunter"] = 300;
$userActions["urlUpdateSecret"] = 300;

// protected action
$userActions["protected"] = 300;

// delete action + delete form
$userActions["urlDelete"] = 200;

// stats action + stats url data
$userActions["urlStats"] = 300;

// show username
$userActions["createdBy"] = 400;

// show deleted url
$userActions["deleted"] = 500;

// reporturl
$userActions["urlReport"] = 200;


$userActions["userUpdate"] = 200;
$userActions["userUpdateUsername"] = 200;
$userActions["userUpdateEmail"] = 300;
$userActions["userUpdateCountry"] = 200;
$userActions["userUpdateDob"] = 200;



// moderation
// can update or delete other users link as well
$userActions["canUpdateLink"] = 400;
$userActions["canDeleteLink"] = 400;
$userActions["canCheckStats"] = 400;

$userActions["canUpdateUser"] = 500;
$userActions["canDeleteUser"] = 500;

?>