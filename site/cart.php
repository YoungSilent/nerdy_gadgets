<?php
include "cartfuncties.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Winkelwagen</title>
</head>

<body>
<h1>Inhoud Winkelwagen</h1>

<?php
$cart = getCart();
print_r($cart);
//gegevens per artikelen in $cart (naam, prijs, etc.) uit database halen
//totaal prijs berekenen
//mooi weergeven in html
//etc.

?>
<p><a href='view.php'>Naar artikelpagina</a></p>

</body>
</html>