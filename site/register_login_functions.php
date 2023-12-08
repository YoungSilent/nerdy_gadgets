<?php
function getCustomer($CustomerID){
    $databaseConnection = connectToDatabase();
    $Query = "
    SELECT  CustomerID
    FROM customers AS cus";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $CustomerID);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $Result;
}