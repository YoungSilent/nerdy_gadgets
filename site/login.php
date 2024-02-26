<?php
include __DIR__ . "/header.php";
include __DIR__ . "/register_login_functions.php";
require_once "database.php";
?>
<?php
if (isset($_POST['submit'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "nerdygadgets";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $FullName = $_POST['FullName'];
    $HashedPassword = $_POST['HashedPassword'];

    $sql = "SELECT CASE
            WHEN FullName = ? THEN PreferredName
            WHEN FullName = ? THEN FullName
            WHEN FullName = ? THEN EmailAddress
         END AS SelectedField,
         HashedPassword
         FROM people
         WHERE FullName = ? OR FullName = ? OR FullName = ?";


    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, "ssssss", $PreferredName, $FullName, $EmailAddress, $PreferredName, $FullName, $EmailAddress);

    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    print $sql;

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
<div style="text-align:center;">
<h2>Login Pagina</h2>
<form method="post" action="ingelogd.php" onSubmit="return validate();"
"">
<label class="formLabel">Email:</label>
<input type="text" name="FullName" style="width: 500px;"><br>
<label class="formLabel">Wachtwoord:</label>
<input id="password" type="password" name="HashedPassword" style="width: 500px;"><br>
<input name="submit" type="submit" value="Inloggen" style="width: 150px; margin-top: 20px; font-size: 20px">
<h5 style="margin-top:10px">Nog geen account klik <a href="register.php">hier</a></h5>
</form>
</div>
</body>
</html>

