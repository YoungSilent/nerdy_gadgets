<?php
require_once 'database.php';
require_once 'review_functions.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $review_id = $_POST['review_id'];
    $StockItemID = $_POST['StockItemID'];
    $rating = $_POST['rating'];
    $beschrijving = $_POST['beschrijving'];
    $conn = connectToDatabase();
    if ($conn) {
        $success = updateReview($review_id, $rating, $beschrijving, $conn);
        if ($success) {
            header("Location: view.php?id=$StockItemID");
            exit();
        } else {
            // Handle update failure
            echo "Failed to update review. Please try again.";
        }
    } else {
        echo "Failed to connect to the database.";
    }
} else {
    echo "Invalid request.";
}
?>
