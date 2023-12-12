<?php
function getOrderSummary($id){
    $databaseConnection = connectToDatabase();
    $Query = "
    SELECT  StockItemName, OL.UnitPrice, OL.Quantity
    FROM orders AS ORD
    JOIN orderlines AS OL ON OL.OrderID = ORD.OrderID
    JOIN stockitems AS SI on SI.StockItemID = OL.StockItemID
    WHERE ORD.OrderID = ?";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $Result;
}

function createOrder($customerID){
    $databaseConnection = connectToDatabase();
    $currentDate = date("Y-m-d");
    $currentDateTime = date("Y-m-d H:i:s");
    $deliverydate = date("Y-m-d", strtotime("tomorrow"));
    $ContactPersonID = getRandomContactID();

    $Query = "INSERT INTO orders (CustomerID, SalespersonPersonID, ContactPersonID, OrderDate, ExpectedDeliveryDate, IsUndersupplyBackordered, LastEditedBy, LastEditedWhen)
    VALUES (?, 1, ?, ?, ?, 1, 1, ?)";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "iisss", $customerID, $ContactPersonID, $currentDate, $deliverydate, $currentDateTime);
    mysqli_stmt_execute($Statement);

    $id = mysqli_insert_id($databaseConnection);
    return $id;
}

function getRandomContactID(){
    //(SELECT PersonID FROM people 
    //WHERE IsEmployee = 1 AND 
    //ORDER BY RAND() LIMIT 1), 
    return 1;
}