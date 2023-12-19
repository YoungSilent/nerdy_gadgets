<?php
include __DIR__ . "/header.php";
include __DIR__ . "/paymentfuncties.php";

$populair = getPopularItems();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artikel Slideshow</title>
    <style>
       /* Stijling voor de slide en tabs */
       #slideshow {
            position: relative;
            width: 100%;
            margin: auto;
        }

        .slide {
            display: none;
            text-align: left;
        }

        .tab {
            cursor: pointer;
            float: left;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            width: 10%;
            height: 50px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tab-row {
          position: center;
        }

        .tab:hover {
            background-color: #bbb;
        }

        .tab.active {
            background-color: #ccc;
        }

        /* Clear floats after the tabs */
        .tab-row:after {
            content: "";
            display: table;
            clear: both;
        }

    </style>
</head>
<body>
    <div id="slideshow">
        <div id="slides">
            <?php for($i = 0; $i < count($populair); $i++): ?>
                <?php
                $stockItem = getStockItem($populair[$i]['StockItemID'], $databaseConnection);
                $image = getStockItemImage($populair[$i]['StockItemID'], $databaseConnection);
                $backupImage = getBackupStockItemImage($populair[$i]['StockItemID'], $databaseConnection);
                ?>
                <div class="slide">
                    <?php if (isset($image[0]["ImagePath"])): ?>
                        <div class="ImgFrame" style="background-image: url('<?php echo "Public/StockItemIMG/" . $image[0]['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php elseif (isset($backupImage[0]['ImagePath'])): ?>
                        <div class="ImgFrame" style="background-image: url('<?php echo "Public/StockGroupIMG/" . $backupImage[0]['ImagePath']; ?>'); background-size: cover;"></div>
                    <?php endif; ?>
                    <h2><?php echo htmlspecialchars($stockItem["StockItemName"], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <!-- Hier kan je extra informatie zoals prijs en beschrijving toevoegen -->
                </div>
            <?php endfor; ?>
        </div>

        <!-- Tab links -->
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
</body>
</html>