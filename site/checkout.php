<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>

<form method="post" action=".php">
       E-mail* <input type="text" name="email" value="" required>
       Voornaam* <input type="text" name="voornaam" value="" required>
       Achternaam* <input type="text" name="achternaam" value="" required>
       Postcode* <input type="text" name="postcode" value="" required>
       Huisnummer* <input type="text" name="huisnummer" value="" required>
       Telefoonnummer <input type="text" name="achternaam" value="">
       <input type="submit" value="Verzenden">
</form>
<br><p>* Verplichte velden</p>


<?php
include __DIR__ . "/footer.php";
?>