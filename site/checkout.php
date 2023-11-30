<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/stockidname.php";
$backupImage = FALSE;
$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);
if (empty($StockItemImage)) {
    $StockItemImage = getBackupStockItemImage($_GET['id'], $databaseConnection);
    $backupImage = TRUE;
}
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
        <form method="post" action=".php">
            E-mail* <input id="checkout" type="text" name="Email" value="" required>
            Voornaam* <input id="checkout" type="text" name="Voornaam" value="" required>
            Achternaam* <input id="checkout" type="text" name="Achternaam" value="" required>
            Postcode* <input id="checkout" type="text" name="Postcode" value="" required>
            Huisnummer* <input id="checkout" type="text" name="Huisnummer" value="" required>
            Telefoonnummer <input id="checkout" type="text" name="Telefoonnummer" value="">
            <br>
            <p>* Verplicht veld</p>

        </form>
    </div>
    <div id="artikel">
    </div>
    <div id="divPrijsEnBetaalKnop">
        <?php
        $ProductInfo = getStockItemInfo();
        foreach ($ProductInfo as $productName) {
            print($productName["StockItemName"] . "<br>");
        }
        ?>


        <br>
        <!--        laat de totaal prijs zien van het winkelmandje-->
        <div id="prijs">
            <?php
            print(getCartPrice());
            ?>
        </div>
        <!--        doorgaan knop om naar de ideal pagina te gaan-->
        <input id="submit" type="submit" value="Doorgaan">
        <?php
        include __DIR__ . "/footer.php";
        ?>
    </div>
</div>