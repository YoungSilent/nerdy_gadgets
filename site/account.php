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
<h2 style="text-align: center;">Jouw Profiel</h2>
<h3 style="text-align: center;">Gebruikersnaam: <?php echo isset($userData['PreferredName']) ? ucwords($userData['PreferredName']) : ''; ?></h3>
<h3 style="text-align: center;">Email: <?php echo isset($userData['EmailAddress']) ? $userData['EmailAddress'] : ''; ?></h3>
<h4 style="text-align: center;">Uitloggen klik <a href="uitloggen.php" onclick="return confirm('Weet u zeker dat u wilt uitloggen?');">hier</a></h4>
</body>
</html>