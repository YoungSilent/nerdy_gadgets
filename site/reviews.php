<?php
// Include database connection file
require_once 'database.php';
require_once 'review_functions.php';
// Check if the user is logged in
if (!isset($_SESSION['PersonID'])) {
    echo "Please log in to make a review.";
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
    $anoniem = $_POST['anoniem'];
    insertReview($StockItemID, $rating, $beschrijving, $time, $date, $personID, $anoniem, $stmt);
}

$huidigItem = getStockItem($_GET['id'], $stmt);
$reviews = displayReviews($huidigItem, $stmt);
// Laat elke review zien
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
    .stars {
        direction: rtl;
    }

    .stars input[type="radio"] {
        display: none;
    }

    .stars label {
        font-size: 30px;
        cursor: pointer;
        color: #ccc;
    }

    .stars label:hover,
    .stars label:hover ~ label,
    .stars input[type="radio"]:checked ~ label {
        color: #ffcc00;
    }

    .anoniem {
        height: 25px;
        width: 25px;
    }
</style>
<?php if ($can_leave_review): ?>
<h1>Add Review</h1>
<form method="POST" action="view.php?id=<?php echo $_GET['id'] ?>">
    <input type="hidden" name="StockItemID" value="<?php echo $_GET['id'] ?>">
    <label>Naam: <?php echo $_SESSION['PreferredName'] ?> </label><br>
    <label for="rating">Beoordeling:</label><br>
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
    <textarea name="beschrijving" id="beschrijving" rows="4" cols="50" required></textarea><br>
    <input class="anoniem" type="hidden" name="anoniem" value="0">
    <input class="anoniem" type="checkbox" name="anoniem" value="1">
    <label for="anoniem">Anoniem plaatsen</label>
    <input type="submit" value="Submit Review">
    <?php endif ?>
</form>
</body>
</html>