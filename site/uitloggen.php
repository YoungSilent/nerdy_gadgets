<?php
$_SESSION = array();
session_destroy();

if (!isset($_SESSION['PersonID'])) {
    // If the user is not logged in, redirect to login.php
    header("Location: login.php");
    exit();
}