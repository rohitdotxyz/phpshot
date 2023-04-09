<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

include("./config.php");

$userErrors = array();

requestCheck($_SERVER["REQUEST_METHOD"],"POST");

if (!array_key_exists($_SERVER['HTTP_STRIPE_SIGNATURE'], $_SERVER) && !$_SERVER['HTTP_STRIPE_SIGNATURE']) {
    $userErrors["restrict"] = 'restricted page';
    $response = makeResponse("fail", 400, $userErrors, NULL);
    $json = json_encode($response);
    echo $json;
}

require_once '../vendor/autoload.php';
$stripe = new \Stripe\StripeClient($webSettings["stripeSecret"]);

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'your webhook secret';

$reqBody = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $reqBody, $sig_header, $endpoint_secret
    );

} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        $session = $event->data->object;
        // handle other event types
        $orderedAtDate = date("Y-m-d H:i:s",$event->created);
        $orderedTimeInSec = strtotime($orderedAtDate);
        $expiredAtDate = date("Y-m-d 11:59:59", strtotime($orderedAtDate . "+29 DAY"));
        $expiredTimeInSec = strtotime($expiredAtDate);


        $orderedById = $session->client_reference_id;
        $invoiceId = $session->payment_intent;

        // Insert Data into DB
        $orderConfirmQuery = "INSERT INTO subscriptions (sub_userId, sub_At, sub_expAt, sub_invoice) VALUES ('$orderedById','$orderedTimeInSec','$expiredTimeInSec', '$invoiceId')";
        $mysqli->query($orderConfirmQuery);

        $updateRoleQuery = "UPDATE users SET user_role = 300, expiresAt = '$expiredTimeInSec' WHERE user_id = '$orderedById'";
        $mysqli->query($updateRoleQuery);

        $data = array();
        $data["orderId"] = $mysqli->insert_id;

        $response = makeResponse("success", 200, $data, NULL);
        $json = json_encode($response);
        echo $json;
    return false;

    default:
        $userErrors["unknown"] = 'Received unknown event type ' . $event->type;
        $response = makeResponse("fail", 400, $userErrors, NULL);
        $json = json_encode($response);
        echo $json;
    return false;
}

http_response_code(200);

?>