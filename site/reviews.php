<?php
// Include database connection file
require_once 'database.php';
require_once 'review_functions.php';
// Check if the user is logged in
if (!isset($_SESSION['PersonID'])) {
    echo "Please log in to make a review.";
    exit;

}

// Initialize $stmt
$stmt = connectToDatabase();
// Handle form submission to add a review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StockItemID = $_POST['StockItemID'];
    $rating = $_POST['rating'];
    $beschrijving = $_POST['beschrijving'];
    $time = date("H:i:s");
    $date = date("Y-m-d");
    $personID = $_SESSION['PersonID'];
    insertReview($StockItemID, $rating, $beschrijving, $time, $date, $personID, $stmt);
}

// Display reviews for StockItemID
$huidigItem = getStockItem($_GET['id'], $stmt);
$reviews = displayReviews($huidigItem, $stmt);
// Output the reviews
foreach ($reviews as $review) {
    echo "Name: " . $review['PreferredName'] . "<br>";
    echo "Rating: " . $review['rating'] . "<br>";
    echo "Description: " . $review['beschrijving'] . "<br>";
    echo "Time: " . $review['time'] . "<br>";
    echo "Date: " . $review['date'] . "<br><br>";
}
// Close $stmt if it's not null
if ($stmt !== null) {
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
</head>
<body>
<style>
    .stars input[type="radio"] {
        display: none;
    }
    .stars label {
        font-size: 30px;
        cursor: pointer;
        color: #ccc;
    }
    .stars label:hover,
    .stars label:hover {
        color: #ffcc00;
    }
    .stars input[type="radio"]:checked {
        color: #ffcc00;
    }
</style>
<h1>Add Review</h1>
<form method="POST" action="view.php?id=<?php echo $_GET['id'] ?>">
    <input type="hidden" name="StockItemID" value="<?php echo $_GET['id'] ?>">
    <label>Naam: <?php echo $_SESSION['PreferredName'] ?> </label><br>
    <label for="rating">Beoordeling:</label>
    <div class="stars">
        <?php
        for ($i = 1; $i <= 10; $i++) {
            echo '<input type="radio" id="rating'.$i.'" name="rating" value="'.$i.'"><label for="rating'.$i.'">&#9733;</label>';
        }
        ?>
    </div>
    <label for="beschrijving">Description:</label><br>
    <textarea name="beschrijving" id="beschrijving" rows="4" cols="50" required></textarea><br>
    <input type="submit" value="Submit Review">
</form>
</body>
</html>