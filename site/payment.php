<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";

?>
<h1>Uw bestelling wordt verwerkt</h1>
<?php

if(getCartPrice()>100) {
    $verzendkosten = 0.00;
}else{
    $verzendkosten = 10.00;
}
$totalValue = number_format((float)getCartTotalPrice($verzendkosten), 2, ".", "");


$method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);

$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR",
        "value" => $totalValue
    ],
    "description" => "My first API payment",
    "redirectUrl" => "https://webshop.example.org/order/12345/",
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
    "method"      => \Mollie\Api\Types\PaymentMethod::IDEAL,
    "issuer"      => $selectedIssuerId, // e.g. "ideal_INGBNL2A"
]);

header("Location: " . $payment->getCheckoutUrl(), true, 303);