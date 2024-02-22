<?php
// Include database connection file
require_once 'database.php';
require_once 'review_functions.php';

if (!isset($_SESSION['PersonID'])) {
    exit;
} else {
    $customer_id = $_SESSION['PersonID'];
    // haal het product id op uit de url
    $product_id = $_GET['id'];
    $conn = connectToDatabase();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $can_leave_review = can_leave_review($conn, $customer_id, $product_id);
    $conn->close();
}
$sort = isset($_SESSION['sort']) ? $_SESSION['sort'] : 'desc';
if (isset($_POST['sort'])) {
    $_SESSION['sort'] = $_POST['sort'];
    $sort = $_SESSION['sort'];
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check of de filter staat ingesteld
    if (isset($_POST['filter_rating'])) {
        $filterRating = $_POST['filter_rating'];

        // sla de filter voor het aantal sterren op in een sessie
        $_SESSION['filter_rating'] = $filterRating;
    }
}
$sessionFilterRating = isset($_SESSION['filter_rating']) ? $_SESSION['filter_rating'] : 'all';
if (isset($_POST['filter_rating'])) {
    $_SESSION['filter_rating'] = $_POST['filter_rating'];
    $sessionFilterRating = $_SESSION['filter_rating'];
}
$stmt = connectToDatabase();
//invoegen van formulier review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StockItemID = $_GET['id'];
    // check of alle gegevens aanwezig zijn dit om error codes te voorkomen tijdens het filteren
    if(isset($_POST['rating'], $_POST['beschrijving'], $_POST['anoniem'])) {
        $rating = $_POST['rating'];
        $beschrijving = $_POST['beschrijving'];
        $anoniem = $_POST['anoniem'];
        $time = date("H:i:s");
        $date = date("Y-m-d");
        $personID = $_SESSION['PersonID'];
        insertReview($StockItemID, $rating, $beschrijving, $time, $date, $personID, $anoniem, $stmt);
    }
}
$huidigItem = getStockItem($_GET['id'], $stmt);
$reviews = displayReviews($huidigItem, $stmt);
?>
    <!--dit is het menu voor het selecteren van de datum op nieuw of oud-->
    <form id="sortFormDate" method="post" action="">
        <label for="sort">Sorteren op datum:</label>
        <select name="sort" id="sort" onchange="submitFormDate()">
            <option value="desc" <?php if ($sort == 'desc') echo 'selected'; ?>>Nieuwste</option>
            <option value="asc" <?php if ($sort == 'asc') echo 'selected'; ?>>Oudste</option>
        </select>
    </form>
    <form id="sortFormRating" method="post" action="">
        <label for="filter_rating">Filter Het Aantal Sterren</label>
        <select name="filter_rating" id="filter_rating" onchange="submitFormRating()">
            <option value="all" <?php if ($sessionFilterRating == 'all') echo 'selected'; ?>>Alle Sterren</option>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?php echo $i ?>" <?php if ($sessionFilterRating == $i) echo 'selected'; ?>><?php echo $i ?></option>
            <?php endfor; ?>
        </select>
    </form>
    <!--Hier onder de complete code voor het maken van de review-->
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Review Plaatsen</title>
    </head>
    <body>
    <div class="form-container clearfix">
    <link rel="stylesheet" href="reviews.css" type="text/css">
    <?php if ($can_leave_review): ?>
    <form method="POST" action="view.php?id=<?php echo $_GET['id'] ?>">
        <input type="hidden" name="StockItemID" value="<?php echo $_GET['id'] ?>">
        <!-- Container for input field and stars -->
        <div class="input-container">
            <textarea type="text" name="beschrijving" id="beschrijving" required></textarea>
            <div class="stars">
                <!-- Sterren voor de beoordeling -->
                <?php for ($i = 10; $i >= 1; $i--): ?>
                    <input required type="radio" id="rating<?php echo $i ?>" name="rating" value="<?php echo $i ?>">
                    <label for="rating<?php echo $i ?>">&#9733;</label>
                <?php endfor; ?>
            </div>
        </div>
        <!-- Checkbox for anonymous review -->
        <input id="AnoniemReviewPlaatsen" class="anoniem" type="hidden" name="anoniem" value="0">
        <input id="AnoniemReviewPlaatsen" class="anoniem" type="checkbox" name="anoniem" value="1">
        <label id="AnoniemReviewPlaatsen" for="anoniem">Anoniem Plaatsen</label>
        <!-- Review Plaatsen Knop -->
        <input id="ReviewPlaatsenSubmit" type="submit" value="Review Plaatsen">
    </form>
    </div>
    </body>
    </html>
<?php
//check of de sessie al sorteer data bevat zo ja zet hem op de standaard descending
$sortOrder = $_SESSION['sort'];
if (isset($_POST['sort'])) {
    $sortOrder = $_POST['sort'];
}

// Sorteer de reviews op datum oud of nieuw
usort($reviews, function ($a, $b) use ($sortOrder) {
    return ($sortOrder == 'asc') ? strtotime($a['date']) - strtotime($b['date']) : strtotime($b['date']) - strtotime($a['date']);
});

    if ($sessionFilterRating == 'all') {
    // Display all reviews
    $filteredReviews = $reviews;

    } else {
    $reviews = array_filter($reviews, function ($review) use ($sessionFilterRating) {
        return $review['rating'] == $sessionFilterRating;
    });
}

// laat de gesorteerde reviews zien
?>
<?php
foreach ($reviews as $review) {
    if ($review['Anoniem'] == 1) {
        $review['PreferredName'] = "anoniem";
    } ?>
    <br>
    <div id="ReviewDiv">
        <div>
            <p id="ReviewNaam"><?php echo "Naam: " . $review['PreferredName']; ?></p>
            <p id="ReviewSterren"><?php echo "Aantal Sterren " . generateStarRating($review['rating']); ?></p>
            <p id="ReviewDatum"><?php echo "Datum: " . $review['date']; ?></p>
        </div>
        <p id="ReviewBeschrijving"><?php echo "Beschrijving: " . $review['beschrijving']; ?></p>
    </div>
    <?php
}
endif ?>