<?php
include __DIR__ . "/header.php";
include __DIR__ . "/register_login_functions.php";
// Check if the user is logged in
if (!isset($_SESSION['PersonID'])) {
    // If the user is not logged in, redirect to login.php
    header("Location: login.php");
    exit();
}
// Get the user data
$userId = $_SESSION['PersonID'];
$userData = getUserData($userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jou Profiel</title>
</head>
<body>
<h2>Jou Profiel</h2>
<h3>Gebruikersnaam <?php echo isset($userData['PreferredName']) ? ucwords($userData['PreferredName']) : ''; ?></h3>
<h3>Email: <?php echo isset($userData['EmailAddress']) ? $userData['EmailAddress'] : ''; ?></h3>
<h4>Uitloggen klik <a href="uitloggen.php">hier</a></h4>
</body>
</html>