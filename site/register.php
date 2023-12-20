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
    $voornaam = $_POST["voornaam"];
    $tussenvoegsel = $_POST["tussenvoegsel"];
    $achternaam = $_POST["achternaam"];
    $fullName = ucwords($voornaam) .' '. $tussenvoegsel .' '. ucwords($achternaam);
    $preferredName = ucwords($_POST['preferredName']);
    $HashedPassword = $_POST['HashedPassword'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $EmailAddress = $_POST['EmailAddress'];
    $ValidFrom = date('Y-m-d h:i:s', time());
    $ValidTo = '9999-12-31 23:59:59';

    // Insert data into people table
    $sqlPeople = "INSERT INTO `people`(`FullName`, `PreferredName`, `SearchName`, `IsPermittedToLogon`, `IsExternalLogonProvider`, `IsSystemUser`, `IsEmployee`, `IsSalesperson`, `LastEditedBy`, `ValidFrom`, `ValidTo`, `HashedPassword`, `EmailAddress`, `PhoneNumber`) 
                  VALUES ('$fullName','$preferredName','$preferredName','1','0','0','0','0','1','$ValidFrom','$ValidTo','$HashedPassword','$EmailAddress','$PhoneNumber')";

    // Execute the query
    $conn->query($sqlPeople);

    // Get the ID of the newly inserted row in the people table
    $lastPersonId = $conn->insert_id;

    // Get address values from the form
    $street = $_POST['Straat'];
    $houseNumber = $_POST['Huisnummer'];
    $postcodeNumbers = $_POST['PostcodeNummers'];
    $postcodeLetters = $_POST['PostcodeLetters'];
    $land = $_POST['Land'];

    // Insert data into customers table
    $sqlCustomers = "INSERT INTO `customers`(`CustomerID`, `PersonID`, `Street`, `HouseNumber`, `PostcodeNumbers`, `PostcodeLetters`, `Land`, `DeliveryAddressLine2`) 
                     VALUES (NULL, '$lastPersonId', '$street', '$houseNumber', '$postcodeNumbers', '$postcodeLetters', '$land', '')";

    // Execute the query
    $conn->query($sqlCustomers);

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
<form method="post" action="geregistreerd.php" onSubmit="return validate();"
"">
<div>
    <div class="formName">
        <label class="formLabel">Voornaam*</label>
        <input id="checkout" type="text" name="voornaam" value="" placeholder="John" required pattern="^[a-zA-Z]+$"
               style="width:175px" maxlength="50">
    </div>
    <div class="formName">
        <label class="formLabel">Tussenvoegsel(s)</label>
        <input id="checkout" type="text" name="tussenvoegsel" value="" placeholder="" pattern="^[a-zA-Z.' ]+$"
               style="width:150px" maxlength="20">
    </div>
    <div class="formName">
        <label class="formLabel">Achternaam*</label>
        <input id="checkout" type="text" name="achternaam" value="" placeholder="Smith" required pattern="^[a-zA-Z']+$"
               style="width:175px" maxlength="50">
    </div>
</div>
<label class="formLabel">Gebruikers naam</label>
<input id="checkout" type="name" name="preferredName" value="" placeholder="Gebruikers naam" required style="width:510px"
       maxlength="100">
<label class="formLabel">E-mail*</label>
<input id="checkout" type="email" name="EmailAddress" value="" placeholder="John@gmail.com" required style="width:510px"
       maxlength="100">
<label class="formLabel">Postcode*</label>
<div>
    <div class="formName">
        <input id="checkout" type="text" name="PostcodeNummers" value="" placeholder="1111" required
               pattern="^[0-9]{4}+$" style="width:80px" minlength="4" maxlength="4">
    </div>
    <div class="formName">
        <input id="checkout" type="text" name="PostcodeLetters" value="" placeholder="AA" required pattern="^[A-Z]{2}+$"
               style="width:65px" minlength="2" maxlength="2">
    </div>
</div>
<div>
    <div class="formName">
        <label class="formLabel">Straat*</label>
        <input id="checkout" type="text" name="Straat" value="" placeholder="Zonnebloemlaan" required
               pattern="^[a-zA-Z]+$" style="width:405px" maxlength="200">
    </div>
    <div class="formName">
        <label class="formLabel">Huisnummer*</label>
        <input id="checkout" type="text" name="Huisnummer" value="" placeholder="112a" required
               pattern="^[0-9]{1,5}[a-zA-Z]{0,1}$" style="width:100px" maxlength="6">
    </div>
</div>
<label class="formLabel">Land*</label>
<input id="checkout" type="text" name="Land" value="" placeholder="Nederland" required pattern="[a-zA-Z]+$"
       style="width:510px" maxlength="200">
<label class="formLabel">Telefoonnummer</label>
<input id="checkout" type="tel" name="PhoneNumber" value="" placeholder="0612345678" pattern="^[0-9]+$" minlength="7"
       maxlength="15" style="width:auto">
<br>
Wachtwoord: <input id="password"  type="password" name="HashedPassword"><br>
Wachtwoord: <input id="confirm_password" type="password" name="HashedPassword"><br>



<p>* Verplicht veld</p>
<script>
    function validate() {

        var a = document.getElementById("password").value;
        var b = document.getElementById("confirm_password").value;
        if (a != b) {
            alert("Passwords do no match");
            return false;
        }
    }
</script>

<input name="submit" type="submit" value="Submit">
</form>
</body>
</html>

