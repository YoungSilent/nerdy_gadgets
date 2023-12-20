<!-- dit is het bestand dat wordt geladen zodra je naar de website gaat -->
<?php
include __DIR__ . "/header.php";
$populair = getPopularItems();
?>
<h1 style="text-align:center">Onze meest populaire producten</h1>
<div id="slideshow">
    <div id="slides">
    <?php for($i = 0; $i < count($populair); $i++): ?>
            <?php
            $stockItem = getStockItem($populair[$i]['StockItemID'], $databaseConnection);
            $image = getStockItemImage($populair[$i]['StockItemID'], $databaseConnection);
            $backupImage = getBackupStockItemImage($populair[$i]['StockItemID'], $databaseConnection);
            ?>
            <div class="slide">
<a href="view.php?id=<?php print(($populair[$i]['StockItemID'])); ?>"><div class="slide-content">
    <?php if (isset($image[0]["ImagePath"])): ?>
        <div class="ImgFrame" style="background-image: url('<?php echo "Public/StockItemIMG/" . $image[0]['ImagePath']; ?>');"></div>
    <?php elseif (isset($backupImage[0]['ImagePath'])): ?>
        <div class="ImgFrame" style="background-image: url('<?php echo "Public/StockGroupIMG/" . $backupImage[0]['ImagePath']; ?>');"></div>
    <?php endif; ?>
    <div class="text-content">
        <h2><?php print($stockItem["StockItemName"]); ?></h2>
        <h4>Prijs (Incl. BTW): €<?php print(number_format((float)$stockItem["SellPrice"], 2, ".", "")); ?></h4>
        <h6>Artikelnummer: <?php print($populair[$i]['StockItemID']); ?></h6>
        <p style="font-size: 14px"><?php print($stockItem['SearchDetails']); ?></p>
    </div>
    </div></a>
</div>
        <?php endfor; ?>
    </div>

    <!-- Tab links -->
</div>
    <div class="tab-row">
    <?php for($i = 0; $i < count($populair); $i++): ?>
            <div class="tab" onclick="currentSlide(<?php echo $i + 1; ?>)">
                Product <?php echo $i + 1; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>

<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    // Functie om van slide te wisselen
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    // Update de huidige slide
    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("slide");
        var tabs = document.getElementsByClassName("tab");

        if (n > slides.length) {
            slideIndex = 1;
        } else if (n < 1) {
            slideIndex = slides.length;
        }

        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
            tabs[i].className = tabs[i].className.replace(" active", "");
        }

        slides[slideIndex-1].style.display = "block";
        tabs[slideIndex-1].className += " active";
    }

    // Automatisch naar de volgende slide na elke 3 seconden (3000ms)
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    setInterval(function() {
        plusSlides(1); // Verplaats naar de volgende slide
    }, 5000);
</script>
<br><br>
<h1 style="text-align:center; color: white"><a href="/cock.php" style="color:white">Zie ook onze andere producten</a></h1>
<div class="IndexStyle">
    <div class="col-11">
        <div class="TextPrice">
            <a href="view.php?id=93">
                <div class="TextMain">
                    "The Gu" red shirt XML tag t-shirt (Black) M
                </div>
                <ul id="ul-class-price">
                    <li class="HomePagePrice">€30.95</li>
                </ul>
        </div>
        </a>
        <div class="HomePageStockItemPicture"></div>
    </div>
</div>



<?php
include __DIR__ . "/footer.php";
?>

