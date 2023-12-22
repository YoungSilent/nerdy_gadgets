<?php
include __DIR__ . "/header.php";
include __DIR__ . "/register_login_functions.php";
require_once "database.php";
?>
<?php
// Check if the form is submitted
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
    $HashedPassword = $_POST['HashedPassword'];

    // SQL query to get data from the people table
    $sql = "SELECT CASE
            WHEN FullName = ? THEN PreferredName
            WHEN FullName = ? THEN FullName
            WHEN FullName = ? THEN EmailAddress
         END AS SelectedField,
         HashedPassword
         FROM people
         WHERE FullName = ? OR FullName = ? OR FullName = ?";


    $statement = mysqli_prepare($conn, $sql);
    // Assuming $PreferredName, $FullName, $EmailAddress are the values you want to check
    mysqli_stmt_bind_param($statement, "ssssss", $PreferredName, $FullName, $EmailAddress, $PreferredName, $FullName, $EmailAddress);

    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    print $sql;

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pagina</title>
</head>
<body>
<h2 style="margin-left: 500px">Login Pagina</h2>
<form method="post" action="ingelogd.php" onSubmit="return validate();"
"">
<label class="formLabel" style="margin-left: 500px">Email:</label>
<input type="text" name="FullName" style="width: 500px; margin-left: 500px;"><br>
<label class="formLabel" style="margin-left: 500px">Wachtwoord:</label>
<input id="password" type="password" name="HashedPassword" style="width: 500px; margin-left: 500px;"><br>
<input name="submit" type="submit" value="Inloggen" style="width: 150px; margin-left: 675px; margin-top: 10px; font-size: 20px">
<h5 style="text-align: center;">Nog geen account klik <a href="register.php">hier</a></h5>
</form>
</body>
</html>

