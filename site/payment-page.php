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

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Zoek de positie van de laatste /
$lastSlashPos = strrpos($url, '/');

// Controleer of er daadwerkelijk een / in de string zit
if ($lastSlashPos !== false) {
    // Haal de substring op tot de laatste / (niet inclusief)
    $trimmedUrl = substr($url, 0, $lastSlashPos);
} else {
    // Geen / gevonden, dus de originele string blijft onveranderd
    $trimmedUrl = $url;
}



//https://github.com/mollie/mollie-api-php

// $order = $mollie->orders->create([
//     "amount" => [
//         "value" => "1027.99",
//         "currency" => "EUR",
//     ],
//     "orderNumber" => "1234",
//     "lines" => [
//         [
//             "name" => "LEGO 42083 Bugatti Chiron",
//             "quantity" => 1,
//             "vatRate" => "21.00",
//             "unitPrice" => [
//                 "currency" => "EUR",
//                 "value" => $totalValue,
//             ],
//             "totalAmount" => [
//                 "currency" => "EUR",
//                 "value" => $totalValue,
//             ],
//             "vatAmount" => [
//                 "currency" => "EUR",
//                 "value" => "0.00",
//             ],
//         ],
//         // more order line items
//     ],
// ]);


$method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);

$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR",
        "value" => $totalValue
    ],
    "description" => "My first API payment",
    "redirectUrl" => $trimmedUrl . "/paymentstatus.php?order=1234",
    "cancelUrl" => $trimmedUrl . "/paymentstatus.php?order=1235",
    "method"      => \Mollie\Api\Types\PaymentMethod::IDEAL,
    "issuer"      => $selectedIssuerId, // e.g. "ideal_INGBNL2A"
]);

header("Location: " . $payment->getCheckoutUrl(), true, 303);