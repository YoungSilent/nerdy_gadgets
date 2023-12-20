<?php
include __DIR__ . "/header.php";
include __DIR__ . "/register_login_functions.php";
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
    $ValidFrom = date('Y-m-d H:i:s', time());
    $ValidTo = '9999-12-31 23:59:59';
    $AccountOpenedDate = $ValidFrom;

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
    $volledigAdres = $street . " " . $houseNumber;
    $postcodeNumbers = $_POST['PostcodeNummers'];
    $postcodeLetters = $_POST['PostcodeLetters'];
    $postcode = $postcodeNumbers . " " . $postcodeLetters;
    $land = $_POST['Land'];

    // Insert data into customers table
    $sqlCustomers = "INSERT INTO `customers`(`CustomerID`, `CustomerName`, `BillToCustomerID`, `CustomerCategoryID`, `PrimaryContactPersonID`, `DeliveryMethodID`, `DeliveryCityID`, `PostalCityID`, `AccountOpenedDate`, `StandardDiscountPercentage`, `IsStatementSent`, `IsOnCreditHold`, `PaymentDays`, `PhoneNumber`, `FaxNumber`, `WebsiteURL`, `DeliveryAddressLine1`, `DeliveryAddressLine2`, `DeliveryPostalCode`, `PostalAddressLine1`, `PostalPostalCode`, `LastEditedBy`, `ValidFrom`, `ValidTo`) 
                 VALUES ($lastPersonId, '$preferredName', '1', '1', '1', '1', '1', '1', '$AccountOpenedDate', '1', '1', '1', '1', '$PhoneNumber', '1', 'http://www.tailspintoys.com/Avenal', '$street', $houseNumber, '$postcode', '$volledigAdres', '$postcodeNumbers $postcodeLetters', '1', '$ValidFrom', '$ValidTo')";

    // Execute the query
    $conn->query($sqlCustomers);

    // Close the connection
    $conn->close();
}
?>

<h2 style="text-align: center;">U Bent Geregistreerd</h2>
<h3 style="text-align: center">Welkom <?php print ucwords($_POST['preferredName']); ?></h3>
