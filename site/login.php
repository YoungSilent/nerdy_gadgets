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
    $username = $_POST['fullName'];
    $HashedPassword = $_POST['HashedPassword'];

    // ... repeat for other form fields ...

    // SQL query to insert data into the people table
    $sql = "SELECT CASE
            WHEN fullName = ? THEN PreferredName
            WHEN fullName = ? THEN FullName
            WHEN fullName = ? THEN EmailAddress
         END AS SelectedField,
         HashedPassword
         FROM people
         WHERE fullName = ? OR fullName = ? OR fullName = ?";


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
    <title>Registreer Pagina</title>
</head>
<body>
<h2>Registreer Pagina</h2>
<form method="post" action="ingelogd.php" onSubmit="return validate();"
"">
Volledige naam: <input type="text" name="fullName"><br>
Wachtwoord: <input id="password" type="password" name="HashedPassword"><br>
<input name="submit" type="submit" value="Submit">
</form>
</body>
</html>

