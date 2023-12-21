<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php

include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php"; 

$backupImage= FALSE;
$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);
if(empty($StockItemImage)){
 $StockItemImage = getBackupStockItemImage($_GET['id'], $databaseConnection);
 $backupImage= TRUE;
}
?>
<?php
//?id=1 handmatig meegeven via de URL (gebeurt normaal gesproken als je via overzicht op artikelpagina terechtkomt)
if (isset($_GET["id"])) {
    $stockItemID = $_GET["id"];
} else {
    $stockItemID = 0;
}
?>  

<div id="CenteredContent">
    <?php
    if ($StockItem != null) {
        ?>
        <?php
        if (isset($StockItem['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $StockItem['Video']; ?>
            </div>
        <?php }




        ?>


        <div id="ArticleHeader">
            <?php
            if (isset($StockItemImage)) {
                // één plaatje laten zien
                if (count($StockItemImage) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/<?php if($backupImage){print("StockGroupIMG/");}else{print("StockItemIMG/");} print $StockItemImage[0]['ImagePath']; ?>'); background-size: <?php if($backupImage){print("cover");}else{print("300px");} ?>; background-repeat: no-repeat; background-position: center;"></div>
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
            ?>


            <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $StockItem['StockItemName']; ?>
            </h2>
            <div class="QuantityText"><?php print $StockItem['QuantityOnHand']; ?></div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $StockItem['SellPrice']); ?></b></p>
                        <h6> Inclusief BTW </h6>
                        <form method="post">
                        <input type="number" name="aantal" min="1" max="<?php echo str_replace("Voorraad: ","",$StockItem['QuantityOnHand'] ) ?>" style="width:80px; background-color:rgba(103,110,255, 1); border:0px; margin-bottom:4px; border-radius:10px; color:white; font-weight:bold;" value="1">
                        <input type="number" name="stockItemID" value="<?php print($stockItemID) ?>" hidden>
                        <input style="font-size:14px; background-color:rgba(103,110,255, 1); border:0px; margin-bottom:4px; border-radius:10px; color:white; font-weight:bold;" type="submit" name="submit" value="Voeg toe aan winkelmandje">
                        </form>
                        <!-- formulier via POST en niet GET om te zorgen dat refresh van pagina niet het artikel onbedoeld toevoegt-->
                        <div style="font-size:10px">
                        <?php
                        if (isset($_POST["submit"])) {              // zelfafhandelend formulier
                            $stockItemID = $_POST["stockItemID"];
                            $stockItemAantal = $_POST["aantal"];
                            addProductToCart($stockItemID, $stockItemAantal);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
                            print("Product toegevoegd aan <a href='cart.php'> winkelmandje!</a>");
                        }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="StockItemDescription">
            <h3>Artikel beschrijving</h3>
            <p><?php print $StockItem['SearchDetails']; ?></p>
        </div>
        <div id="StockItemSpecifications">
            <h3>Artikel specificaties</h3>
            <?php
            $CustomFields = json_decode($StockItem['CustomFields'], true);
            if (is_array($CustomFields)) { ?>
                <table>
                <thead>
                <th>Naam</th>
                <th>Data</th>
                </thead>
                <?php
                foreach ($CustomFields as $SpecName => $SpecText) { ?>
                    <tr>
                        <td>
                            <?php print $SpecName; ?>
                        </td>
                        <td>
                            <?php
                            if (is_array($SpecText)) {
                                foreach ($SpecText as $SubText) {
                                    print $SubText . " ";
                                }
                            } else {
                                print $SpecText;
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </table>
            <?php }
            else { ?>

                <p><?php print $StockItem['CustomFields']; ?>.</p>
                <?php
            }
            ?>
        </div>
        <?php

        /*Luukie's code uu*/

$StockItemID = $_GET["id"];
        $allAanbevelingen = printAanbevelingen(getAanbevelingIDs($_GET['id']));
        $itemsPerGroep = 3;

// Aanbevelingsgroepen
        $huidigeGroep = isset($_GET['groep']) ? intval($_GET['groep']) : 0;

        $totalAanbevelingen = count($allAanbevelingen);
        $totalAantalGroepen = ceil($totalAanbevelingen / $itemsPerGroep);

// Display items in groups of 3
        for ($i = $huidigeGroep * $itemsPerGroep; $i < min(($huidigeGroep + 1) * $itemsPerGroep, $totalAanbevelingen); $i++) {
            print_r($allAanbevelingen[$i]);
            echo "<br>";
        }
        ?>

        <form method="get" action="view.php?">
            <input type="hidden" name="id" value="<?php print($StockItemID)?>">
            <input type="hidden" name="groep" value="<?php print($huidigeGroep + 1) % $totalAantalGroepen; ?>">
            <input type="submit" value="Next Group">
        </form>

    <?php
    }
    else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>

</body>
</html>