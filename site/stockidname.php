<?php
function getStockItemInfo()
{
    $databaseConnection = connectToDatabase();
    $cart = getCart();
    $Query = "SELECT StockItemID, StockItemName, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice
    FROM stockitems SI 
    WHERE SI.StockItemID IN (" . implode(',', array_keys($cart)) . ")";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $Result;
//    foreach ($Result as $ResultKey => $ResultValue) {
//        foreach ($ResultValue as $key => $value) {
//            if ($key === "StockItemName") {
//                $StockItemName = $value;
//                $StockItemImage = getStockItemImage($value, $databaseConnection);
//                $StockBackupItemImage = getBackupStockItemImage($value, $databaseConnection);
//
//            }
//        }
//    }
}

?>