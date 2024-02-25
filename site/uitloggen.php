<?php
session_start();
unset($_SESSION['PersonID']);
unset($_SESSION['FullName']);
unset($_SESSION['PreferredName']);
unset($_SESSION['EmailAddress']);
unset($_SESSION['PhoneNumber']);
unset($_SESSION['PostalAddressLine2']);
// Redirect to the login page
header("Location: index.php");
exit();
