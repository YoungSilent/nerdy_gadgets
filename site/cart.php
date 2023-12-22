<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Winkelwagen</title>
</head>

<body>
<div id="CartContent">
    <?php
    foreach(getCart() as $key => $value){
        if (isset($_POST[$key])) {              // zelfafhandelend formulier
            removeProductFromCart($key);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
        }
        if (isset($_POST["aantal" . $key])) {
            adjustCartProductQuantity($key, $_POST["aantal" . $key]);
        }
    }




    $cart = getCart();
    $totaalPrijs = NULL;
    //print_r($cart);
    //print("<br>");
    //gegevens per artikelen in $cart (naam, prijs, etc.) uit database halen
    //totaal prijs berekenen
    //mooi weergeven in html
    //etc.



    if(empty($cart) == FALSE ){
    $Query = "SELECT StockItemID, StockItemName, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, RecommendedRetailPrice, QuantityOnHand    
    FROM stockitems SI
    JOIN stockitemholdings SIH USING(stockitemid) 
    WHERE SI.StockItemID IN (" . implode(',' , array_keys($cart)) . ")";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

    ?> <div id="CartProducts"> <?php
        //Laat informatie zien over de producten in de winkelwagen
        foreach($Result as $ResultKey => $ResultValue){
            foreach($ResultValue as $key => $value){
                if($key === "StockItemID"){
                    $stockItemID = $value;
                    ?>
                    <div id="ImageFrame"
                         style="width: 175px;
                                 height: 175px;
                                 background-color: rgb(36, 41, 143);
                                 float: left;
                                 margin-right: 10px;
                                 background-image: url('Public/<?php print(getBothStockImages($stockItemID, $databaseConnection)); ?>');
                                 background-size: <?php if(isBackupImage($stockItemID, $databaseConnection)){print("cover");}else{print("175px");} ?>;
                                 background-repeat: no-repeat;
                                 background-position: left;"></div>
                    <?php
                }
            }
            print($ResultValue['StockItemName'] . "<br>");
            ?>Artikelprijs (Incl. BTW): <span style="float:right;"><?php print sprintf("€ %.2f", number_format((float)$ResultValue['SellPrice'], 2, ".", "" . "<br>")); ?> </span>
            <form method="post" action="cart.php">
                <label for="aantal"></label>Aantal:
                <input type="number" id="aantal" style="text-align: right" name="aantal<?php print($stockItemID);?>" min="1" value="<?php print($cart[$stockItemID]);?>"  max="<?php echo str_replace("Voorraad: ","",$ResultValue['QuantityOnHand'] ) ?>">
            </form>
            Totaal artikelprijs (Incl. BTW): <span style="float:right; box-shadow: 0 -1px 0 #FFFFFF;">€ <?php print(number_format((float)$cart[$stockItemID] * number_format((float)$ResultValue['SellPrice'], 2, ".", "" . "<br>"), 2, ".", "" . "<br>")); ?></span>

            <br>
            <form method="post" action="cart.php">
                <button onclick="verwijderenUitWinkelmandje(event)" type="submit" name="<?php echo $stockItemID; ?>" style="border: none; background: none; padding: 5px; margin 0px;">
                    <img src="Public\ProductIMGHighRes\prullenbak.png" alt="Remove from Cart" style="width: auto; height: 40px; /* adjust as needed */">
                </button>
            </form>

            <br><br>
            <?php
        }
        ?>
        <br>
        <p><a href='categories.php' style="color:white; text-decoration:underline;">Terug naar artikelen</a></p>
    </div>
    <?php $verzendkosten = number_format((float)getVerzendkosten(), 2, ".", "") ;
          $_SESSION['verzendkosten'] = $verzendkosten;  ?>
    <div id="CartSummary">
        <p> Aantal producten: <?php print(array_sum($cart));?>
            <br>Subtotaal (Excl. BTW): <span style="float: right;">€ <?php print(number_format((float)getCartPriceZonderBTW(), 2, ".", "")); ?> </span>
            <br>BTW: <span style="float: right;">€ <?php print(number_format((float)number_format((float)getCartPrice(), 2, ".", "") - number_format((float)getCartPriceZonderBTW(), 2, ".", ""), 2, ".", "")); ?> </span>
            <br>Verzendkosten: <span style="float: right;">€ <?php
            print($verzendkosten)?></p></span>
        <?php
        $totaalPrijsFinal = number_format((float)getCartTotalPrice($verzendkosten), 2, ".", "");
        ?>
        <div style="box-shadow: 0 -1px 0 #FFFFFF;">
            Totaalprijs (Incl. BTW): <span style="float: right;">€<?php print($totaalPrijsFinal);?></span>
        </div>
        <div style="font-size:12px;">Zonder evt. toegepaste korting</div>
        <div id="KortingsCode">
            <label style="font-size:14.5px">Voer hier uw kortingscode in. (Niet verplicht*)</label>
            <form method="post" action="cart.php">
                <input type="text" name="kortingsCodeInput" value="" style="width:175px; height:25px; font-size:14.5px;">
            </form>

            <?php
            //Conversiemaatregel Lucas//
            $isGebruikt = TRUE;
            $submittedCoupon = "";
            if(isset($_POST["kortingsCodeInput"])) {
            $submittedCoupon = $_POST["kortingsCodeInput"];

            $Query = "SELECT kortingsPercentage, uses, validUntil
                          FROM globalCoupons
                          WHERE globalCouponCode = '$submittedCoupon';";
            $Statement = mysqli_prepare($databaseConnection, $Query);
            mysqli_stmt_execute($Statement);
            $Result = mysqli_stmt_get_result($Statement);
            if($Result->num_rows > 0) {
            $row = $Result->fetch_assoc();
            if($row["uses"] > 0) {
            if($row["validUntil"] >= date("Y-m-d")) {
            if($isGebruikt) {
            $newUses = $row["uses"] - 1;
            $Query = "UPDATE globalCoupons
                                      SET uses = '$newUses'
                                      WHERE globalCouponCode = '$submittedCoupon';";
            $Statement = mysqli_prepare($databaseConnection, $Query);
            mysqli_stmt_execute($Statement);
            $Result = mysqli_stmt_get_result($Statement);
            $isGebruikt = FALSE;
            $totaalPrijsFinal = number_format((float)($totaalPrijsFinal * ((100 - $row["kortingsPercentage"]) / 100)), 2, ".", "");
            $_SESSION['totaalPrijsFinal'] = $totaalPrijsFinal;
            print("<div class='kortingVerwerking'>Uw korting is verwerkt!\n</div>");
            print("</div><div id='tekstKorting'>Totaalprijs met toegepaste korting (Incl. BTW): <br>");
            ?><div id='prijsKorting'>€<?php print("$totaalPrijsFinal</div>");
                }else{
                    print("U kunt één korting per bestelling gebruiken");
                }
                }else{
                    print("<div class='kortingVerwerking'>Deze korting is verlopen.</div>");
                    $_SESSION['totaalPrijsFinal'] = "";
                }

                }
                }else{
                    print("<div class='kortingVerwerking'>Deze korting bestaat niet.</div>");
                    $_SESSION['totaalPrijsFinal'] = "";
                }
                }else{
                    print("");
                    $_SESSION['totaalPrijsFinal'] = "";
                }
                ?>
            </div>
            <a href="checkout.php">
                <div id="NaarAfrekenen">
                    <form method="post" action="checkout.php">
                        <input type="hidden" name="totaalprijs" value=<?php print($totaalPrijsFinal);?>>
                        <input style="width:auto; border:none; border-radius:10px;" type="submit" name="" value="Afrekenen">
                    </form>
                    <img src="Public\ProductIMGHighRes\afrekenen.png" alt="Afreken Icoontje" id="AfrekenIcon">
                </div>
            </a>
        </div>

        <?php

        }else{
            print("Uw winkelmandje is leeg");
        }
        ?>


        <?php
        include __DIR__ . "/footer.php";
        ?>
    </div>
</body>



        