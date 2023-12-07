<?php
function getCartPrice2(){
    $databaseConnection = connectToDatabase();
    $cart = getCart();

    $Query = "
    SELECT * 
    FROM orders AS ORD
    JOIN orderlines AS OL ON OL.OrderID = ORD.OrderID
    WHERE SI.StockItemID IN (" . implode(',' , array_keys($cart)) . ")";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

}