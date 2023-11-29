<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Public/CSS/checkout.css">
    <title>Checkout</title>
</head>
<body>
<div id="form">
    <form method="post" action=".php">
       E-mail* <input id="checkout" type="text" name="Email" value="" required>
       Voornaam* <input id="checkout" type="text" name="Voornaam" value="" required>
       Achternaam* <input id="checkout" type="text" name="Achternaam" value="" required>
       Postcode* <input id="checkout" type="text" name="Postcode" value="" required>
       Huisnummer* <input id="checkout" type="text" name="Huisnummer" value="" required>
       Telefoonnummer <input id="checkout" type="text" name="Telefoonnummer" value="">
       <input id="submit" type="submit" value="Doorgaan">
</form>
</div>
<br><p>* Verplicht veld</p>
<?php
print(getCartPrice());
include __DIR__ . "/footer.php";
?>