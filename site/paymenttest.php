<?php
include __DIR__ . "/header.php";
include __DIR__ . "/paymentfuncties.php";
$populair = getPopularItems();
for($i = 0; $i <= 4; $i++){
    $stockItem = getStockItem($populair[$i]['StockItemID'], $databaseConnection);
    $image = getStockItemImage($populair[$i]['StockItemID'], $databaseConnection);
    $backupImage = getBackupStockItemImage($populair[$i]['StockItemID'], $databaseConnection);
    print_r($image);
    print_r($backupImage);
    if (isset($image[0]["ImagePath"])) { ?>
        <div class="ImgFrame"
             style="background-image: url('<?php print "Public/StockItemIMG/" . $image[0]['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;"></div>
    <?php } else if (isset($backupImage[0]['ImagePath'])) { ?>
        <div class="ImgFrame"
             style="background-image: url('<?php print "Public/StockGroupIMG/" . $backupImage[0]['ImagePath'] ?>'); background-size: cover;"></div>
    <?php }
    print($stockItem["StockItemName"]);
    print("<br><br><br><br><br><br><br><br><br><br><br>");
    unset($image);
    unset($backupImage);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Slideshow Example</title>
<style>
    .slideshow-container {
        width: 50%;
        margin: auto;
        position: relative;
    }
    .slide {
        display: none;
    }
    .active {
        display: block;
    }
    .prev, .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        margin-top: -22px;
        padding: 16px;
        color: white;
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }
    .prev:hover, .next:hover {
        background-color: rgba(0,0,0,0.8);
    }
</style>
</head>
<body>

<div class="slideshow-container">

<div class="mySlides fade">
  <img src="Public/StockItemIMG/IT joke mug.png.jpg" style="width:100%">
  <div class="text">Caption Text</div>
</div>

<div class="mySlides fade">
  <img src="Public/StockItemIMG/IT joke mug.png" style="width:100%">
  <div class="text">Caption Two</div>
</div>

<div class="mySlides fade">
  <img src="Public/StockItemIMG/IT joke mug.png" style="width:100%">
  <div class="text">Caption Three</div>
</div>

<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>

</div>

<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  slides[slideIndex-1].style.display = "block";  
}
</script>

</body>
</html>