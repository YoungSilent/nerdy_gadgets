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

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $voornaam = $_POST["voornaam"];
    $tussenvoegsel = $_POST["tussenvoegsel"];
    $achternaam = $_POST["achternaam"];
    $fullName = ucwords($voornaam) .' '. $tussenvoegsel .' '. ucwords($achternaam);
    $preferredName = ucwords($_POST['preferredName']);
    $HashedPassword = password_hash($_POST['HashedPassword'], PASSWORD_DEFAULT);
    $PhoneNumber = $_POST['PhoneNumber'];
    $EmailAddress = $_POST['EmailAddress'];
    $ValidFrom = date('Y-m-d h:i:s', time());
    $ValidTo = '9999-12-31 23:59:59';

    // Insert data into people table
    $sqlPeople = "INSERT INTO `people`(`FullName`, `PreferredName`, `SearchName`, `IsPermittedToLogon`, `IsExternalLogonProvider`, `IsSystemUser`, `IsEmployee`, `IsSalesperson`, `LastEditedBy`, `ValidFrom`, `ValidTo`, `HashedPassword`, `EmailAddress`, `PhoneNumber`) 
                  VALUES ('$fullName','$preferredName','$preferredName','1','0','0','0','0','1','$ValidFrom','$ValidTo','$HashedPassword','$EmailAddress','$PhoneNumber')";

    $conn->query($sqlPeople);

    $lastPersonId = $conn->insert_id;

    $street = $_POST['street'];
    $houseNumber = $_POST['Huisnummer'];
    $postcodeNumbers = $_POST['PostcodeNummers'];
    $postcodeLetters = $_POST['PostcodeLetters'];
    $land = $_POST['PostalAddressLine2'];

    $sqlCustomers = "INSERT INTO `customers`(
    `CustomerName`, `CustomerID`, `DeliveryAddressLine1`, `DeliveryAddressLine2`, 
    `DeliveryPostalCode`, `PostalAddressLine2`, `BillToCustomerID`, `CustomerCategoryID`, 
    `PrimaryContactPersonID`, `DeliveryMethodID`, `DeliveryCityID`, `PostalCityID`,
    `AccountOpenedDate`, `StandardDiscountPercentage`, `IsStatementSent`, `IsOnCreditHold`, 
    `PaymentDays`, `PhoneNumber`, `FaxNumber`, `WebsiteURL`, `PostalAddressLine1`, 
    `PostalPostalCode`, `LastEditedBy`, `ValidFrom`, `ValidTo`
) 
VALUES (
    '$fullName', $lastPersonId, '$street', '$houseNumber', '$postcodeNumbers', '$land', '1', '1', '1', 
    1, 1, 1, '2024/01/05', 0, 0, 0, 0, 0, 0, 0, 0, 0, '1', '2024/01/05', '2025/01/01'
);
";

    $conn->query($sqlCustomers);

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
<h2 style="margin-left: 500px; margin-bottom: 25px">Registreer Pagina</h2>
<form method="post" action="geregistreerd.php" onSubmit="return validate();" style="margin-left: 500px">
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
<input id="checkout" type="text" name="PostalAddressLine2" value="" placeholder="Nederland" required pattern="[a-zA-Z]+$"
       style="width:510px" maxlength="200">
<label class="formLabel">Telefoonnummer</label>
<input id="checkout" type="tel" name="PhoneNumber" value="" placeholder="0612345678" pattern="^[0-9]+$" minlength="7"
       maxlength="15" style="width:auto">
<br>

<label class="formLabel">Wachtwoord*</label>
<input id="password" type="password" name="HashedPassword" value="" required style="width:510px"
       minlength="8">
<label class="formLabel">Bevestig Wachtwoord*</label>
<input id="confirm_password" type="password" name="HashedPassword" value="" required style="width:510px"
       minlength="8">

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
    <input name="submit" type="submit" value="Registreren" style="width: 125px; margin-left: 475px; margin-top: 10px">
</form>
</body>
</html>

