<?php
session_start();
include __DIR__ . "/header.php";
include __DIR__ . "/register_login_functions.php";

if (isset($_POST['submit'])) {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nerdygadgets";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get values from the form
    $FullName = $_POST['FullName'];
    $PreferredName = $_POST['FullName'];
    $passwordInput = $_POST['HashedPassword'];

    // SQL query to check if the username or email and hashed password match
    $sql = "SELECT * FROM people WHERE (FullName = ? OR EmailAddress = ?) AND HashedPassword = ?";

    $statement = mysqli_prepare($conn, $sql);

    // Assuming $username and $passwordInput are the values you want to check
    mysqli_stmt_bind_param($statement, "sss", $FullName, $PreferredName, $passwordInput);

    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    // Check if there is a match
    if ($row = mysqli_fetch_assoc($result)) {
        // Match found, user is logged in
        // You can store user information in the session if needed
        $_SESSION['PersonID'] = $row['PersonID'];
        $_SESSION['FullName'] = $row['FullName'];
        $_SESSION['EmailAddress'] = $row['EmailAddress'];
        $_SESSION['PhoneNumber'] = $row['PhoneNumber'];

        // Redirect to a logged-in page
        header("Location: ingelogd.php");
        exit();
    } else {
        // No match found, handle the error or redirect to a login page
        echo "Invalid username or password";
    }

    // Close the connection
    $conn->close();
}
?>

<!-- Your HTML content for the logged-in page -->
<h2 style="text-align: center;"><?php echo !isset($_SESSION['PersonID']) ? "Uw wachtwoord of email was onjuist" : ''; ?></h2>
<h2 style="text-align: center;"><?php echo isset($_SESSION['PersonID']) ? "U Bent Ingelogd" : ''; ?></h2>
<h3 style="text-align: center"><?php echo isset($_SESSION['FullName']) ? "Welkom" . " " . ucwords($_SESSION['FullName']) : ''; ?></h3>

<!--<h3 style="text-align: center">--><?php //echo isset($_SESSION['EmailAddress']) ? "Email: " . " " . $_SESSION['EmailAddress'] : ''; ?><!--</h3>-->
<!--<h3 style="text-align: center">personID: --><?php //echo isset($_SESSION['PersonID']) ? $_SESSION['PersonID'] : ''; ?><!--</h3>-->
