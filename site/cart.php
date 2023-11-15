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
<h1>Winkelwagen :</h1>

<?php
foreach(getCart() as $key => $value){
    if (isset($_POST[$key])) {              // zelfafhandelend formulier
        removeProductFromCart($key);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
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
    $Query = "SELECT StockItemID, StockItemName, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice
    FROM stockitems SI 
    WHERE SI.StockItemID IN (" . implode(',' , array_keys($cart)) . ")";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

    //Laat informatie zien over de producten in de winkelwagen
    foreach($Result as $ResultKey => $ResultValue){
            foreach($ResultValue as $key => $value){
                if($key === "StockItemID"){
                    $stockItemID = $value;
                    $StockItemImage = getStockItemImage($value, $databaseConnection);
                    $StockBackupItemImage = getBackupStockItemImage($value, $databaseConnection);
                    if(empty($StockItemImage) == FALSE){
                    ?>
                        <div id="ImageFrame"
                             style="background-image: url('Public/StockItemIMG/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: left;"></div>
                    <?php }else{?>
                        <div id="ImageFrame"
                            style="background-image: url('Public/StockGroupIMG/<?php print $StockBackupItemImage[0]['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: left;"></div>
                    <?php }
                }elseif($key === "SellPrice"){
                    $totaalPrijs = (number_format((float)$value, 2, ".", "") * $cart[$stockItemID])+ $totaalPrijs;                
                    print("€" . number_format((float)$value, 2, ".", "") . "<br>");
                }else{
                    print($value . "<br>");  
                }
            }
            print("Aantal: " . $cart[$stockItemID]);

            ?>
            <form method="post" action="cart.php">
            <input type="submit" name="<?php print($stockItemID); ?>" value="Verwijder uit winkelmandje">
            </form>

            <?php
            }
            ?>
            <p><a href='browse.php'>Naar artikelpagina</a></p>
            <br><p>Totaal prijs: €<?php print(number_format((float)$totaalPrijs, 2, ".", "")); ?></p>
            <form method="post" action="checkout.php">
            <input type="hidden" name="totaalprijs" value="<?php print($totaalPrijs); ?>">
            <input type="submit" name="" value="Afrekenen">
            </form>
            <?php
            
        }else{
            print("Uw winkelmandje is leeg");
        }
include __DIR__ . "/footer.php";
?>