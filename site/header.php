<!-- de inhoud van dit bestand wordt bovenaan elke pagina geplaatst -->
<?php
if(session_status() === PHP_SESSION_NONE) session_start();
include "database.php";
include "customerfuncties.php";
$databaseConnection = connectToDatabase();



//namespace _PhpScoperc4e61a44a745;

/*
 * Make sure to disable the display of errors in production code!
 */
\ini_set('display_errors', '1');
\ini_set('display_startup_errors', '1');
\error_reporting(\E_ALL);
require_once __DIR__ . "/Public/Mollie/vendor/autoload.php";
require_once __DIR__ . "/functions.php";
/*
 * Initialize the Mollie API library with your API key.
 *
 * See: https://www.mollie.com/dashboard/developers/api-keys
 */
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey("test_rGnM6JAEuHQ3pAG8pwtPtH4J9FhacU");



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="Public/JS/fontawesome.js"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/resizer.js"></script>
    <script src="Public/JS/alerts.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/typekit.css">
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="./" id="LogoImage">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-8" id="CategoriesBar">
            <ul id="ul-class">
                <?php
                $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>
<!-- code voor US3: zoeken en winkelwagen icon-->

        <ul id="ul-class-navigation">
            <li>
                <a href="cart.php" class="HrefDecoration">
                    <img src="./Public/ProductIMGHighRes/shoppingCartIcon.png" style="width:40px; height:40px; margin-right:10px;"></a>
            </li>

            <li>
                <a href="browse.php" class="HrefDecoration"><i class="fas fa-search search"></i> Zoeken</a>
            </li>
        </ul>

<!-- einde code voor US3 zoeken en winkelwagen icon-->
    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">


