<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/stockidname.php";
if(getCartPrice()>100) {
    $verzendkosten = 0.00;
}else{
    $verzendkosten = 10.00;
}
// $backupImage = FALSE;
// $StockItem = getStockItem($_GET['id'], $databaseConnection);
// $StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);
// if (empty($StockItemImage)) {
//     $StockItemImage = getBackupStockItemImage($_GET['id'], $databaseConnection);
//     $backupImage = TRUE;
// }
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Public/CSS/checkout.css">
    <title>Checkout</title>
</head>
<body>
<div id="wrapper">
    <div id="form">
        <form method="post" action="payment.php" id="form1">
            Voornaam* <input id="checkout" type="text" name="Voornaam" value="" placeholder="John" required pattern="^[a-zA-Z]+$">
            Tussenvoegsels <input id="checkout" type="text" name="Tussenvoegsel" value="" placeholder="" pattern="^[a-zA-Z.' ]+$">
            Achternaam* <input id="checkout" type="text" name="Achternaam" value="" placeholder="Smith" required pattern="^[a-zA-Z']+$">
            E-mail* <input id="checkout" type="email" name="Email" value="" placeholder="John@gmail.com" required>
            Postcode Nummers* <input id="checkout" type="text" name="PostcodeNummers(deze remained)" value="" placeholder="1111" required pattern="^[0-9]{4}+$">
            Postcode Letters* <input id="checkout" type="text" name="PostcodeLetters" value="" placeholder="AA" required pattern="^[A-Z]{2}+$">
            Straat* <input id="checkout" type="text" name="Straat" value="" placeholder="Zonnebloemlaan" required pattern="^[a-zA-Z]+$">
            Huisnummer* <input id="checkout" type="text" name="Huisnummer" value="" placeholder="112a" required pattern="^[0-9]{1,5}[a-zA-Z]{0,1}$">
            Land* <input id="checkout" type="text" name="Land" value="" placeholder="Nederland" required pattern="[a-zA-Z]+$">
            Telefoonnummer <input id="checkout" type="tel" name="Telefoonnummer" value="" placeholder="0612345678" pattern="^[0-9]+$" minlength="7" maxlength="15">
            <br>
            <p>* Verplicht veld</p>

        </form>
    </div>
    <div id="artikel">
    </div>
    <div id="divPrijsEnBetaalKnop">
        <?php
        $ProductInfo = getStockItemInfo();
        $cart = getCart();
        foreach ($ProductInfo as $productName) {
            print($productName["StockItemName"] . "<br>");
            print("€" . number_format((float)$productName["SellPrice"], 2, ".", "") . " X ");
            print($cart[$productName["StockItemID"]]);
            print(": " . "€" . getArtikelPrice($productName["StockItemID"]) . "<br>" );
            print("<br>");
        }
        ?>
        <!--        laat de totaal prijs zien van het winkelmandje-->
        <div id="prijs">
            <?php
            print("Subtotaal(incl btw): €");
            print(getCartPrice());
            print("<br>");
            print("Verzend kosten: €" . number_format((float)$verzendkosten, 2, ".", ""));
            print("<br>");
            print("Totaalprijs (incl btw): €" . number_format((float)getCartTotalPrice($verzendkosten), 2, ".", ""));
            ?>
        </div>
        <!--        doorgaan knop om naar de ideal pagina te gaan-->
        <br>

        <form method="post" action="payment.php" onsubmit="return validateForm1AndSubmit();" id="form2">
            <input id="submit" type="submit" value="Doorgaan">
        </form>
        <?php


        include __DIR__ . "/footer.php";
        ?>
    </div>
</div>