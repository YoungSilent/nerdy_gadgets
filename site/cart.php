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
<h1>Inhoud Winkelwagen</h1>

<?php
$cart = getCart();
//print_r($cart);
print("<br>");
//gegevens per artikelen in $cart (naam, prijs, etc.) uit database halen
//totaal prijs berekenen
//mooi weergeven in html
//etc.




$Query = "SELECT StockItemID, StockItemName, RecommendedRetailPrice
FROM stockitems SI 
WHERE SI.StockItemID IN (" . implode(',' , array_keys($cart)) . ")";
$Statement = mysqli_prepare($databaseConnection, $Query);
mysqli_stmt_execute($Statement);
$Result = mysqli_stmt_get_result($Statement);
$Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

foreach($Result as $key => $value){
        foreach($value as $key => $value){
            if($key === "StockItemID"){
                $StockItemImage = getStockItemImage($value, $databaseConnection);
                if (isset($StockItemImage)) {

                    
                    // één plaatje laten zien
                    if (count($StockItemImage) == 1) {
                        ?>
                        <div id="ImageFrame"
                             style="background-image: url('Public/StockItemIMG/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                        <?php
                    } else if (count($StockItemImage) >= 2) { ?>
                        <!-- meerdere plaatjes laten zien -->
                        <div id="ImageFrame">
                            <div id="ImageCarousel" class="carousel slide" data-interval="false">
                                <!-- Indicators -->
                                <ul class="carousel-indicators">
                                    <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                        ?>
                                        <li data-target="#ImageCarousel"
                                            data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                        <?php
                                    } ?>
                                </ul>
    
                                <!-- slideshow -->
                                <div class="carousel-inner">
                                    <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                        ?>
                                        <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                            <img src="Public/StockItemIMG/<?php print $StockItemImage[$i]['ImagePath'] ?>">
                                        </div>
                                    <?php } ?>
                                </div>
    
                                <!-- knoppen 'vorige' en 'volgende' -->
                                <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </a>
                                <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockGroupIMG/<?php print $StockItem['BackupImagePath']; ?>'); background-size: cover;"></div>
                    <?php
                }

            }else{

            print($value . "<br>");
            }
        }
        }  




            
?>
<p><a href='view.php?id=0'>Naar artikelpagina van artikel 0</a></p>
</body>

</html>