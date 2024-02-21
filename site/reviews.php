<?php
// Include database connection file
require_once 'database.php';
require_once 'review_functions.php';


if (!isset($_SESSION['PersonID'])) {
    exit;
} else {
    $customer_id = $_SESSION['PersonID'];

    // Get the product ID from the URL
    $product_id = $_GET['id']; // Assuming product_id is passed in the URL
    $conn = connectToDatabase();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $can_leave_review = can_leave_review($conn, $customer_id, $product_id);
    $conn->close();
}
//maak de sort in de sessie aan
if(isset($_SESSION['sort'])) {
    $sort = 'desc';
}
if(isset($_POST['sort'])) {
    $_SESSION['sort'] = $_POST['sort'];
    $sort = $_SESSION['sort'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if filter options are set
    if (isset($_POST['filter_rating'])) {
        $filterRating = $_POST['filter_rating'];

        // Save the selected filter rating in a session variable
        $_SESSION['filter_rating'] = $filterRating;
    }
}
$sessionFilterRating = isset($_SESSION['filter_rating']) ? $_SESSION['filter_rating'] : 'all';
if(isset($_SESSION['filter_rating'])) {
    $sessionFilterRating = 'all';
}
if(isset($_POST['filter_rating'])) {
    $_SESSION['filter_rating'] = $_POST['filter_rating'];
    $sessionFilterRating = $_SESSION['filter_rating'];
}

$stmt = connectToDatabase();
//invoegen van formulier review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $StockItemID = $_POST['StockItemID'];
        $rating = $_POST['rating'];
        $beschrijving = $_POST['beschrijving'];
        $time = date("H:i:s");
        $date = date("Y-m-d");
        $personID = $_SESSION['PersonID'];
        $anoniem = $_POST['anoniem'];
        insertReview($StockItemID, $rating, $beschrijving, $time, $date, $personID, $anoniem, $stmt);
}

$huidigItem = getStockItem($_GET['id'], $stmt);
$reviews = displayReviews($huidigItem, $stmt);
?>

<!--dit is het menu voor het selecteren van de datum op nieuw of oud-->
<form id="sortFormDate" method="post" action="">
    <label for="sort">Sorteren op datum:</label>
    <select name="sort" id="sort" onchange="submitFormDate()">
        <option value="desc" <?php if($sort == 'desc') echo 'selected'; ?>>Nieuwste</option>
        <option value="asc" <?php if($sort == 'asc') echo 'selected'; ?>>Oudste</option>
    </select>
</form>
<form id="sortFormRating" method="post" action="">
    <label for="filter_rating">Filter Het Aantal Sterren</label>
    <select name="filter_rating" id="filter_rating" onchange="submitFormRating()">
        <option value="all" <?php if($sessionFilterRating == 'all') echo 'selected'; ?>>All Ratings</option>
        <option value="1" <?php if($sessionFilterRating == '1') echo 'selected'; ?>>1</option>
        <option value="2" <?php if($sessionFilterRating == '2') echo 'selected'; ?>>2</option>
        <option value="3" <?php if($sessionFilterRating == '3') echo 'selected'; ?>>3</option>
        <option value="4" <?php if($sessionFilterRating == '4') echo 'selected'; ?>>4</option>
        <option value="5" <?php if($sessionFilterRating == '5') echo 'selected'; ?>>5</option>
        <option value="6" <?php if($sessionFilterRating == '6') echo 'selected'; ?>>6</option>
        <option value="7" <?php if($sessionFilterRating == '7') echo 'selected'; ?>>7</option>
        <option value="8" <?php if($sessionFilterRating == '8') echo 'selected'; ?>>8</option>
        <option value="9" <?php if($sessionFilterRating == '9') echo 'selected'; ?>>9</option>
        <option value="10" <?php if($sessionFilterRating == '10') echo 'selected'; ?>>10</option>
    </select>
</form>
<!--kort script voor het aanpassen van de data in het label zonder een knop-->
<script>
    function submitFormDate() {
        document.getElementById("sortFormDate").submit();
    }
    function submitFormRating() {
        document.getElementById("sortFormRating").submit();
    }
</script>
<?php
//check of de sessie al sorteer data bevat zo ja zet hem op de standaard descending
$sortOrder = 'desc';
if (isset($_POST['sort'])) {
    $sortOrder = $_POST['sort'];
}


// Sorteer de reviews op datum oud of nieuw
usort($reviews, function($a, $b) use ($sortOrder) {
    return ($sortOrder == 'asc') ? strtotime($a['date']) - strtotime($b['date']) : strtotime($b['date']) - strtotime($a['date']);
});
if ($sessionFilterRating == 'all') {
    // Display all reviews
    $filteredReviews = $reviews;
} else {
    $reviews = array_filter($reviews, function($review) use ($sessionFilterRating) {
        return $review['rating'] == $sessionFilterRating;
    });
}
// laat de gesorteerde reviews zien
foreach ($reviews as $review) {
    if ($review['Anoniem'] == 1) {
        $review['PreferredName'] = "anoniem";
    }
    echo "Name: " . $review['PreferredName'] . "<br>";
    echo "Rating: " . $review['rating'] . "<br>";
    echo "Description: " . $review['beschrijving'] . "<br>";
    echo "Time: " . $review['time'] . "<br>";
    echo "Date: " . $review['date'] . "<br><br>";
}
?>

<!--Hier onder de complete code voor het maken van de review-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
</head>
<body>
<link rel="stylesheet" href="reviews.css" type="text/css">
<?php if ($can_leave_review): ?>
<h1>Add Review</h1>
<form method="POST" action="view.php?id=<?php echo $_GET['id'] ?>">
    <input type="hidden" name="StockItemID" value="<?php echo $_GET['id'] ?>">
    <label>Naam: <?php echo $_SESSION['PreferredName'] ?> </label><br>
    <label for="rating">Beoordeling:</label><br>
<!--    elke knop als een ster-->
    <div class="stars">
        <input type="radio" id="rating10" name="rating" value="10">
        <label for="rating10">&#9733;</label>
        <input type="radio" id="rating9" name="rating" value="9">
        <label for="rating9">&#9733;</label>
        <input type="radio" id="rating8" name="rating" value="8">
        <label for="rating8">&#9733;</label>
        <input type="radio" id="rating7" name="rating" value="7">
        <label for="rating7">&#9733;</label>
        <input type="radio" id="rating6" name="rating" value="6">
        <label for="rating6">&#9733;</label>
        <input type="radio" id="rating5" name="rating" value="5">
        <label for="rating5">&#9733;</label>
        <input type="radio" id="rating4" name="rating" value="4">
        <label for="rating4">&#9733;</label>
        <input type="radio" id="rating3" name="rating" value="3">
        <label for="rating3">&#9733;</label>
        <input type="radio" id="rating2" name="rating" value="2">
        <label for="rating2">&#9733;</label>
        <input type="radio" id="rating1" name="rating" value="1">
        <label for="rating1">&#9733;</label>
    </div>
    <label for="beschrijving">Description:</label><br>
<!--    hier kan de gebruiker commentaar toevoegen-->
    <textarea name="beschrijving" id="beschrijving" rows="4" cols="50" required></textarea><br>
<!--    checkbox voor het anoniem versturen van de review-->
    <input class="anoniem" type="hidden" name="anoniem" value="0">
    <input class="anoniem" type="checkbox" name="anoniem" value="1">
    <label for="anoniem">Anoniem plaatsen</label>
<!--    knop voor het opslaan en versturen van de review-->
    <input type="submit" value="Review Plaatsen">
    <?php endif ?>
</form>
</body>
</html>