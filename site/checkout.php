<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/stockidname.php";
include __DIR__ . "/paymentfuncties.php";
include __DIR__ . "/register_login_functions.php";
if (getCartPrice() > 100) {
    $verzendkosten = 0.00;
} else {
    $verzendkosten = 10.00;
}

if (isset($_SESSION['PersonID'])) {
    $userData = getUserData($_SESSION['PersonID']);
    $streetData = getStreetData($_SESSION['PersonID']);
    $FullName = $_SESSION['FullName'];
        $PhoneNumber = $_SESSION['PhoneNumber'];
        $EmailAddress = $_SESSION['EmailAddress'];
        $Straat = $streetData['PostalAddressLine1'];
        $PostCodeTotaal = $streetData['PostalPostalCode'];
        $Land = $streetData['PostalAddressLine2'];

// Split full name into an array
    $nameParts = explode(' ', $FullName);
    $PostCodeDelen = explode(' ', $PostCodeTotaal);
    $straatsplitten = explode(' ', $Straat);


// Extract first and last names
    $firstName = isset($nameParts[0]) ? $nameParts[0] : '';
    $lastName = end($nameParts);
    // Extract "extra" part
    unset($nameParts[0]); // Remove the first name
    array_pop($nameParts); // Remove the last name
    $extra = implode(' ', $nameParts);
    $huisnummer = end($straatsplitten);
    $straatnaam = ($straatsplitten[0]);
    $PostCodeCijfers = isset($PostCodeDelen[0]) ? $PostCodeDelen[0] : '';
    $PostCodeLetters = isset($PostCodeDelen[1]) ? $PostCodeDelen[1] : '';
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
        <form method="post" action="payment.php" id="form1">
            <div>
                <div class="formName">
                    <label class="formLabel">Voornaam*</label>
                    <input id="checkout" type="text" name="Voornaam" value="<?php echo isset($firstName) ? htmlspecialchars($firstName):""; ?>"
                           placeholder="John" required pattern="^[a-zA-Z]+$" style="width:175px" maxlength="50">
                </div>
                <div class="formName">
                    <label class="formLabel">Tussenvoegsel(s)</label>
                    <input id="checkout" type="text" name="Tussenvoegsel"
                           value="<?php echo isset($firstName) ? htmlspecialchars($extra):""; ?>" placeholder="" pattern="^[a-zA-Z.' ]+$"
                           style="width:150px" maxlength="20">
                </div>
                <div class="formName">
                    <label class="formLabel">Achternaam*</label>
                    <input id="checkout" type="text" name="Achternaam"
                           value="<?php echo isset($firstName) ? htmlspecialchars($lastName):""; ?>" placeholder="Smith" required
                           pattern="^[a-zA-Z']+$" style="width:175px" maxlength="50">
                </div>
            </div>
            <label class="formLabel">E-mail*</label>
            <input id="checkout" type="email" name="Email" value="<?php echo isset($firstName) ? htmlspecialchars($EmailAddress):""; ?>" placeholder="John@gmail.com" required
                   style="width:510px" maxlength="100">
            <label class="formLabel">Postcode*</label>
            <div>
                <div class="formName">
                    <input id="checkout" type="text" name="PostcodeNummers" value="<?php echo isset($firstName) ? htmlspecialchars($PostCodeCijfers):""; ?>" placeholder="1111" required
                           pattern="^[0-9]{4}+$" style="width:80px" minlength="4" maxlength="4">
                </div>
                <div class="formName">
                    <input id="checkout" type="text" name="PostcodeLetters" value="<?php echo isset($firstName) ? htmlspecialchars($PostCodeLetters):""; ?>" placeholder="AA" required
                           pattern="^[A-Z]{2}+$" style="width:65px" minlength="2" maxlength="2">
                </div>
            </div>
            <div>
                <div class="formName">
                    <label class="formLabel">Straat*</label>
                    <input id="checkout" type="text" name="Straat" value="<?php echo isset($firstName) ? htmlspecialchars($straatnaam):""; ?>" placeholder="Zonnebloemlaan" required
                           pattern="^[a-zA-Z]+$" style="width:405px" maxlength="200">
                </div>
                <div class="formName">
                    <label class="formLabel">Huisnummer*</label>
                    <input id="checkout" type="text" name="Huisnummer" value="<?php echo isset($firstName) ? htmlspecialchars($huisnummer):""; ?>" placeholder="112a" required
                           pattern="^[0-9]{1,5}[a-zA-Z]{0,1}$" style="width:100px" maxlength="6">
                </div>
            </div>
            <label class="formLabel">Land*</label>
            <input id="checkout" type="text" name="Land" value="<?php echo isset($firstName) ? htmlspecialchars($Land):""; ?>" placeholder="Nederland" required pattern="[a-zA-Z]+$"
                   style="width:510px" maxlength="200">
            <label class="formLabel">Telefoonnummer</label>
            <input id="checkout" type="tel" name="Telefoonnummer" value="<?php echo isset($firstName) ? htmlspecialchars($PhoneNumber):""; ?>" placeholder="0612345678" pattern="^[0-9]+$"
                   minlength="7" maxlength="15" style="width:auto">
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
                print(": " . "€" . getArtikelPrice($productName["StockItemID"]) . "<br>");
                print("<br>");
            }
            ?>
            <!--        laat de totaal prijs zien van het winkelmandje-->
        </div>
        <?php $verzendkosten = $_SESSION['verzendkosten'];?>
        <div id="prijs">
            Subtotaal (Excl. BTW): <span
                    style="float: right;">€ <?php print(number_format((float)getCartPriceZonderBTW(), 2, ".", "")); ?> </span>
            <br>BTW: <span
                    style="float: right;">€ <?php print(number_format((float)number_format((float)getCartPrice(), 2, ".", "") - number_format((float)getCartPriceZonderBTW(), 2, ".", ""), 2, ".", "")); ?> </span>
            <br>Verzendkosten: <span style="float: right;">€ <?php
                print($verzendkosten) ?></p></span>
            <div id="totaalPrijs">
                <br>
                Totaal prijs (Incl. BTW): <span
                        style="float: right;">€<?php print(" ".number_format((float)getCartTotalPrice($verzendkosten), 2, ".", "")); ?></span>
                <?php if (isset($_SESSION['totaalPrijsFinal']) && $_SESSION['totaalPrijsFinal'] != "") {
                    $totaalPrijsFinal = $_SESSION['totaalPrijsFinal'];
                print("
                <br>
                Totaal prijs met korting (Incl. BTW): <span
                    id = 'checkoutMetKorting'>€ $totaalPrijsFinal</span>");
                }?>
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