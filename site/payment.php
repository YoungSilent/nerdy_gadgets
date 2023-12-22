<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/paymentfuncties.php";

?>
<h1>Uw bestelling wordt verwerkt</h1>
<?php

if(getCartPrice()>100) {
    $verzendkosten = 0.00;
}else{
    $verzendkosten = 10.00;
}
$totalValue = number_format((float)getCartTotalPrice($verzendkosten), 2, ".", "");

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$lastSlashPos = strrpos($url, '/');
if ($lastSlashPos !== false) {
    $trimmedUrl = substr($url, 0, $lastSlashPos);
} else {
    $trimmedUrl = $url;
}


$_SESSION['customerID'] = 10;
$customerID = $_SESSION['customerID'];
$orderID = createOrder($customerID);

//https://github.com/mollie/mollie-api-php


$method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);

$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR",
        "value" => $_SESSION['totaalPrijsFinal']
    ],
    "description" => "My first API payment",
    "redirectUrl" => $trimmedUrl . "/orderstatus.php?order=" . $orderID,
    "cancelUrl" => $trimmedUrl . "/paymentstatus.php?status=cancelled",
    "method"      => \Mollie\Api\Types\PaymentMethod::IDEAL,
]);

header("Location: " . $payment->getCheckoutUrl(), true, 303);