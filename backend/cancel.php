<?php


include('./config.php');

$userErrors = array();

if (!$isLoggedIn) {
    $userErrors["signin"] = "Please signin.";
    $response = makeResponse("fail", 400, $userErrors, 2);
    $json = json_encode($response);
    echo $json;
    return false;
}

// if (
//     !isset($_SERVER["HTTP_REFERER"]) || 
//     !$_SERVER["HTTP_REFERER"]
// ) {
//     $userErrors["signin"] = "Server Error.";
//     $response = makeResponse("fail", 500, $userErrors, 2);
//     $json = json_encode($response);
//     echo $json;
//     return false;
// }



?>