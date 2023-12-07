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

function insertOrder(){
    //mysqli_query($databaseConnection, "INSERT INTO mytable (1, 2, 3, 'blah')");

    $databaseConnection = connectToDatabase();
    $cart = getCart();

    $Query = "
    SELECT StockItemID, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice
    FROM stockitems SI 
    WHERE SI.StockItemID IN (" . implode(',' , array_keys($cart)) . ")";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

    $id = mysqli_insert_id($databaseConnection);
    return $id;
}