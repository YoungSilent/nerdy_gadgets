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
        <div>
            <div class="formName">
                <label class="formLabel">Voornaam*</label>    
                <input id="checkout" type="text" name="Voornaam" value="" placeholder="John" required pattern="^[a-zA-Z]+$" style="width:175px">
            </div>
            <div class="formName">
                <label class="formLabel">Tussenvoegsel(s)</label>
                <input id="checkout" type="text" name="Tussenvoegsel" value="" placeholder="" pattern="^[a-zA-Z.' ]+$" style="width:150px">
            </div>
            <div class="formName">
                <label class="formLabel">Achternaam*</label>
                <input id="checkout" type="text" name="Achternaam" value="" placeholder="Smith" required pattern="^[a-zA-Z']+$" style="width:175px">
            </div>
        </div>
            <label class="formLabel">E-mail*</label>
            <input id="checkout" type="email" name="Email" value="" placeholder="John@gmail.com" required style="width:510px">
        <label class="formLabel">Postcode*</label>
        <div>
            <div class="formName">
                <input id="checkout" type="text" name="PostcodeNummers(deze remained)" value="" placeholder="1111" required pattern="^[0-9]{4}+$" style="width:80px">
            </div>
            <div class="formName">
                <input id="checkout" type="text" name="PostcodeLetters" value="" placeholder="AA" required pattern="^[A-Z]{2}+$" style="width:65px">
            </div>
        </div>
        <div>
            <div class="formName">
                <label class="formLabel">Straat*</label>
                <input id="checkout" type="text" name="Straat" value="" placeholder="Zonnebloemlaan" required pattern="^[a-zA-Z]+$">
            </div>
            <div class="formName">
                <label class="formLabel">Huisnummer*</label>
                <input id="checkout" type="text" name="Huisnummer" value="" placeholder="112a" required pattern="^[0-9]{1,5}[a-zA-Z]{0,1}$" style="width:100px">
            </div>
        </div>
            <label class="formLabel">Land*</label>
            <input id="checkout" type="text" name="Land" value="" placeholder="Nederland" required pattern="[a-zA-Z]+$" style="width:510px">
            <label class="formLabel">Telefoonnummer</label>
            <input id="checkout" type="tel" name="Telefoonnummer" value="" placeholder="0612345678" pattern="^[0-9]+$" minlength="7" maxlength="15" style="width:auto">
            <br>
            <p>* Verplicht veld</p>

        </form>
    </div>
    <div id="divPrijsEnBetaalKnop">
    <div id="artikelen">
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
    </div>
        <div id="prijs">
            Subtotaal (Excl. BTW): <span style="float: right;">€ <?php print(number_format((float)getCartPriceZonderBTW(), 2, ".", "")); ?> </span>
            <br>BTW: <span style="float: right;">€ <?php print(number_format((float)number_format((float)getCartPrice(), 2, ".", "") - number_format((float)getCartPriceZonderBTW(), 2, ".", ""), 2, ".", "")); ?> </span>
            <br>Verzendkosten: <span style="float: right;">€ <?php
                                        if(getCartPrice()>100) {
                                            $verzendkosten = 0.00;
                                            print(number_format((float)$verzendkosten, 2, ".", ""));
                                        }else{
                                            $verzendkosten = 10.00;
                                            print(number_format((float)$verzendkosten, 2, ".", ""));
                                        }?></p></span>                            
            <div id="totaalPrijs">
            <br>    
            Totaal prijs (Incl. BTW): <span style="float: right;">€<?php print(number_format((float)getCartTotalPrice($verzendkosten), 2, ".", ""));?></span>
            </div>
        </div>
        <!--        doorgaan knop om naar de ideal pagina te gaan-->
        <br>

        <form method="post" action="payment.php" onsubmit="return validateForm1AndSubmit();" id="form2">
            <input id="submit" type="submit" value="Doorgaan">
        </form>
    </div>
</div>
<?php
include __DIR__ . "/footer.php";
?>