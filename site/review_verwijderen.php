<?php
require_once 'database.php';
session_start();
if (!isset($_SESSION['PersonID'])) {
//    var_dump($_POST['review_id']);
//    var_dump($_SESSION['PersonID']);
    exit;

}
$connection = connectToDatabase();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reviewID = $_POST['review_id'];
    $userID = $_SESSION['PersonID'];
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
