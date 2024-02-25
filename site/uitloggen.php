<?php
session_start();
unset($_SESSION['PersonID']);
// Redirect to the login page
header("Location: index.php");
exit();
