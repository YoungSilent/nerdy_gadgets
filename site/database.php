<!-- dit bestand bevat alle code die verbinding maakt met de database -->
<?php
function connectToDatabase() {
    $Connection = null;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
    try {
        $Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
        mysqli_set_charset($Connection, 'latin1');
        $DatabaseAvailable = true;
    } catch (mysqli_sql_exception $e) {
        $DatabaseAvailable = false;
    }
    if (!$DatabaseAvailable) {
        ?><h2>Website wordt op dit moment onderhouden.</h2><?php
        die();
    }

    return $Connection;
}


function getHeaderStockGroups($databaseConnection) {
    $Query = "
                SELECT StockGroupID, StockGroupName, ImagePath
                FROM stockgroups 
                WHERE StockGroupID IN (
                                        SELECT StockGroupID 
                                        FROM stockitemstockgroups
                                        ) AND ImagePath IS NOT NULL
                ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $HeaderStockGroups = mysqli_stmt_get_result($Statement);
    return $HeaderStockGroups;
}

function getStockGroups($databaseConnection) {
    $Query = "
            SELECT StockGroupID, StockGroupName, ImagePath
            FROM stockgroups 
            WHERE StockGroupID IN (
                                    SELECT StockGroupID 
                                    FROM stockitemstockgroups
                                    ) AND ImagePath IS NOT NULL
            ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $StockGroups = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $StockGroups;
}

function getStockItem($id, $databaseConnection) {
    $Result = null;

    $Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
            SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}

function getStockItemImage($id, $databaseConnection) {

    $Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}

function getBackupStockItemImage($id, $databaseConnection) {

    $Query = "
                SELECT ImagePath 
                FROM stockgroups
                JOIN stockitemstockgroups USING(StockGroupID) 
                WHERE StockItemID = ?
                LIMIT 1";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}

function getBothStockImages($id, $databaseConnection) {
    $StockItemImage = getStockItemImage($id, $databaseConnection);
    $StockBackupItemImage = getBackupStockItemImage($id, $databaseConnection);
    if(empty($StockItemImage) == FALSE){
        return "StockItemIMG/" . $StockItemImage[0]['ImagePath']; 
     }else{
        return "StockGroupIMG/" . $StockBackupItemImage[0]['ImagePath'];
     }
}

function isBackupImage($id, $databaseConnection) {
    if(empty(getStockItemImage($id, $databaseConnection))){
        return TRUE; 
     }else{
        return FALSE;
     }
}

function getPopularItems() {
    $databaseConnection = connectToDatabase();
    $Query = "SELECT StockItemID 
    FROM orderlines AS OLS
    JOIN orders AS ORD ON ORD.OrderID = OLS.OrderID
    JOIN stockitemholdings SIH USING(stockitemid)
    WHERE orderdate > DATE_SUB(CURDATE(), INTERVAL 8 YEAR)
    AND QuantityOnHand > 1
    GROUP BY StockItemID
    ORDER BY count(*) DESC, QuantityOnHand DESC, StockItemID ASC
    LIMIT 5;";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $Result;
}

/*Luuk: Vraagt op basis van het meegegeven ItemID van het weergegeven item, de bijpassende aanbevelingen op.*/
function getAanbevelingIDs($id) {
    $databaseConnection = connectToDatabase();
    $Result = null;

    $Query = "
select A.AanbevolenGroep1, A.AanbevolenGroep2, A.AanbevolenGroep3, A.AanbevolenGroep4
from stockitems S
left join Aanbevelingen A on S.GroepID=A.GroepID 
where StockItemID = $id";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
        return $Result;
}}

function printAanbevelingen ($aanbevelingGroepIDs) {
    $databaseConnection = connectToDatabase();
    $Result = null;
    $allResults = array();

Foreach ($aanbevelingGroepIDs as $aanbevelingsCategorie) {
if ($aanbevelingsCategorie != '' and $aanbevelingsCategorie != null){
    $Query = " 
           select StockItemName, ROUND(TaxRate * RecommendedRetailPrice / 100 + RecommendedRetailPrice,2) as SellPrice, StockItemID
            from stockitems
            where GroepID = $aanbevelingsCategorie";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC);
    $allResults = $allResults + $Result;
    }
}
    Return $allResults;
}

function getStockItemForOrderLines($id, $databaseConnection) {
    $Result = null;

    $Query = " 
           SELECT 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            SearchDetails, OuterPackageID, UnitPrice, TaxRate
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY SI.StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}
