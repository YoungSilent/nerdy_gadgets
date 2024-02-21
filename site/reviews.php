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
$sortOrderRating = 'asc'; // Default sort order for ratings
$sort = 'asc';
if(isset($_POST['sort_rating'])) {
    $_SESSION['sort_rating'] = $_POST['sort_rating'];
    $sortOrderRating = $_SESSION['sort_rating'];
}
if(isset($_POST['sort'])) {
    $_SESSION['sort'] = $_POST['sort'];
    $sort = $_SESSION['sort'];
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
<form id="sortForm" method="post" action="">
    <label for="sort">Sorteren op datum:</label>
    <select name="sort" id="sort" onchange="submitForm()">
        <option value="desc" <?php if($sort == 'desc') echo 'selected'; ?>>Nieuwste</option>
        <option value="asc" <?php if($sort == 'asc') echo 'selected'; ?>>Oudste</option>
    </select>
</form>
<form id="sortFormRating" method="post" action="">
    <label for="sortFormRating">Sorteren op datum:</label>
    <select name="sortFormRating" id="sortFormRating" onchange="submitFormRating()">
        <option value="desc" <?php if($sortOrderRating == 'desc') echo 'selected'; ?>>Nieuwste</option>
        <option value="asc" <?php if($sortOrderRating == 'asc') echo 'selected'; ?>>Oudste</option>
    </select>
</form>

<script>
    function submitForm() {
        document.getElementById("sortForm").submit();
    }
    function submitFormRating() {
        document.getElementById("sortFormRating").submit();
    }

</script>
<?php
$sortOrder = 'desc';
if (isset($_POST['sort'])) {
    $sortOrder = $_POST['sort'];
}

// Sort the reviews array by date
usort($reviews, function($a, $b) use ($sortOrder) {
    return ($sortOrder == 'asc') ? strtotime($a['date']) - strtotime($b['date']) : strtotime($b['date']) - strtotime($a['date']);
});
$sortOrderRating = 'desc';
if (isset($_POST['sort_rating'])) {
    $sortOrderRating = $_POST['sort_rating'];
}

// Sort the reviews array by rating
usort($reviews, function($a, $b) use ($sortOrderRating) {
    if ($a['rating'] == $b['rating']) {
        return 0;
    }
    return ($sortOrderRating == 'asc') ? ($a['rating'] < $b['rating'] ? -1 : 1) : ($a['rating'] > $b['rating'] ? -1 : 1);
});
// Display sorted reviews
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
    <input type="submit" value="Review Plaatsen">
    <?php endif ?>
</form>
</body>
</html>