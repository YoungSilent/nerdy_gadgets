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
<!--Display the review form-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
</head>
<body>
<h1>Add Review</h1>
<form method="POST" action="view.php?id=">
    <input type="hidden" name="StockItemID" value="<?php echo $StockItemID ?>">
    <label>Naam:</label>
    <label for="rating">Rating:</label>
    <input type="number" name="rating" id="rating" min="1" max="5" required><br>
    <label for="beschrijving">Description:</label><br>
    <textarea name="beschrijving" id="beschrijving" rows="4" cols="50" required></textarea><br>
    <input type="submit" value="Submit Review">
</form>
</body>
</html>
