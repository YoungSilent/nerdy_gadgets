<?php
require_once 'database.php';
session_start();
if (!isset($_SESSION['PersonID'])) {
    // Redirect the user to the login page or display an error message
    var_dump($_POST['review_id']);
    var_dump($_SESSION['PersonID']);

//    exit; // Stop further execution of the script
}
$connection = connectToDatabase();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get review ID from the form submission
    $reviewID = $_POST['review_id'];
    $userID = $_SESSION['PersonID']; // Get the user ID from the session
    // Perform the deletion of the review from the database
    $query_delete = "DELETE FROM reviews WHERE id = $reviewID AND PersonID = $userID";
    $result_delete = mysqli_query($connection, $query_delete);
}
$StockItemID = $_POST['StockItemID'];

if ($result_delete) {
//    var_dump($StockItemID);
    header("Location: view.php?id=$StockItemID");
} else {
    echo "Error deleting review: " . mysqli_error($connection);
}
