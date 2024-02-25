<?php
require_once 'database.php';
require_once 'review_functions.php';
session_start();
$conn = connectToDatabase();
$StockItemID = $_POST['StockItemID'];
if(isset($_POST['rating'], $_POST['beschrijving'], $_POST['anoniem'])) {
    $rating = $_POST['rating'];
    $beschrijving = $_POST['beschrijving'];
    $anoniem = $_POST['anoniem'];
    $time = date("H:i:s");
    $date = date("Y-m-d");
    $personID = $_SESSION['PersonID'];
    insertReview($StockItemID, $rating, $beschrijving, $time, $date, $personID, $anoniem, $conn);
}
header("Location: view.php?id=$StockItemID");
