<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Artikel Slideshow</title>
    <style>
/* Stijling voor de slideshow container */
#slideshow {
    position: relative;
    width: 100%;
    margin: auto;
    display: flex;
    justify-content: center;
}

/* Aangepaste stijling voor de tekst naast de afbeelding */
.text-content {
    border: 1px solid black; /* Rand toegevoegd rond de tekst */
    padding: 10px; /* Ruimte binnen de rand */
    margin-left: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    max-width: 400px; /* Stel een maximale breedte in voor de tekst */
}

/* Aangepaste stijling voor de slide */
.slide {
    display: flex;
    text-align: center;
    justify-content: center;
    align-items: center;
    width: 100%;
}

/* Volledige containment block voor afbeelding en titel */
.slide-content {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start; /* Begin aan de start van de flex container */
    padding: 20px 0; /* Padding aan de boven- en onderkant */
}

/* Afbeelding frame */
.ImgFrame {
    /* Stel een minimale breedte in */
    min-width: 300px; /* Dit dient overeen te komen met de breedste afbeelding */
    /* Of stel een vaste hoogte in */
    min-height: 230px; /* Dit zorgt ervoor dat alle afbeeldingen dezelfde hoogte hebben */
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    flex-shrink: 0; /* Voorkom dat de afbeelding krimpt */
}

/* Stijling voor de tab rij */
.tab-row {
    display: flex;
    justify-content: center;
    margin-top: 20px; /* Spacing between slides and tabs */
}

.tab {
    cursor: pointer;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    padding: 10px 20px;
    margin: 0 5px; /* Spacing between tabs */
    user-select: none;
}

.tab.active {
    background-color: #ccc;
}

.tab:hover {
    background-color: #ddd;
}
    </style>
</head>
<body>
    <?php
    include __DIR__ . "/header.php";
    include __DIR__ . "/paymentfuncties.php";
$populair = getPopularItems();
    ?>
    <div id="slideshow">
        <div id="slides">
        <?php for($i = 0; $i < count($populair); $i++): ?>
                <?php
                $stockItem = getStockItem($populair[$i]['StockItemID'], $databaseConnection);
                $image = getStockItemImage($populair[$i]['StockItemID'], $databaseConnection);
                $backupImage = getBackupStockItemImage($populair[$i]['StockItemID'], $databaseConnection);
                ?>
                <div class="slide">
    <div class="slide-content">
        <?php if (isset($image[0]["ImagePath"])): ?>
            <div class="ImgFrame" style="background-image: url('<?php echo "Public/StockItemIMG/" . $image[0]['ImagePath']; ?>');"></div>
        <?php elseif (isset($backupImage[0]['ImagePath'])): ?>
            <div class="ImgFrame" style="background-image: url('<?php echo "Public/StockGroupIMG/" . $backupImage[0]['ImagePath']; ?>');"></div>
        <?php endif; ?>
        <div class="text-content">
            <h2><?php echo htmlspecialchars($stockItem["StockItemName"], ENT_QUOTES, 'UTF-8'); ?></h2>
        </div>
        </div>
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
</body>
</html>