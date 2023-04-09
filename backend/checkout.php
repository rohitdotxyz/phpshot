<?php

include('./config.php');


$userInputs = array(
	'xtoken' => ''
);

$userErrors = array(
	'xtoken' => '',
	'signin' => '',
);

$requiredFields = array(
    'xtoken' => true,
	'signin' => true,
);


requestCheck($_SERVER["REQUEST_METHOD"],"POST");


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
    $userErrors["signin"] = "Please signin to report url.";
}


if (array_filter($userErrors)) {
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
    return false;
}


$product = array(
    "id" => "5c25af0d-433b-4b20-b8e5-ea958bdf20bc",
    "name" => "phpshort",
    "desc" => "url shortening service for long urls",
    "price" => 100,
);



require_once '../vendor/autoload.php';
$stripe = new \Stripe\StripeClient($webSettings["stripeSecret"]);

$checkout_session = $stripe->checkout->sessions->create([
    'line_items' => [
        [
            'price_data' => [
                'currency' => 'inr',
                'product_data' => [
                    'name' => $product["name"],
                    'description' => $product["desc"],
                ],
                'unit_amount' => $product["price"] * 100,
            ],
            'quantity' => 1,
        ]
    ],
    'payment_method_types' => ['card'],
    'customer_email' => $user_info['email'],
    'client_reference_id' => $user_info["id"],
    'mode' => 'payment',
    'success_url' => $domain.'/myurls.php?payment=success',
    'cancel_url' => $domain.'/myurls.php?payment=cancel',
]);



$response = makeResponse("success", 200, $checkout_session, NULL);
$json = json_encode($response);
echo $json;

// echo "<pre>";
// echo json_encode($checkout_session);
// echo "</pre>";

// header("HTTP/1.1 303 See Other");
// header("Location: " . $checkout_session->url);

?>