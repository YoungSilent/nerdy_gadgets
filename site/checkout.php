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
       E-mail* <input id="checkout" type="text" name="email" value="" required>
       Voornaam* <input id="checkout" type="text" name="voornaam" value="" required>
       Achternaam* <input id="checkout" type="text" name="achternaam" value="" required>
       Postcode* <input id="checkout" type="text" name="postcode" value="" required>
       Huisnummer* <input id="checkout" type="text" name="huisnummer" value="" required>
       Telefoonnummer <input id="checkout" type="text" name="achternaam" value="">
       <input type="submit" value="Verzenden">
</form>
</div>
<br><p>* Verplichte velden</p>
<?php
print(getCartPrice());



include __DIR__ . "/footer.php";
?>