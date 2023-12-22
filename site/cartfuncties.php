<?php
if(session_status() === PHP_SESSION_NONE) session_start();                                // altijd hiermee starten als je gebruik wilt maken van sessiegegevens

function getCart(){
    if(isset($_SESSION['cart'])){               //controleren of winkelmandje (=cart) al bestaat
        $cart = $_SESSION['cart'];                  //zo ja:  ophalen
    } else{
        $cart = array();                            //zo nee: dan een nieuwe (nog lege) array
    }
    return $cart;                               // resulterend winkelmandje terug naar aanroeper functie
}

function saveCart($cart){
    $_SESSION["cart"] = $cart;                  // werk de "gedeelde" $_SESSION["cart"] bij met de meegestuurde gegevens
}

function addProductToCart($stockItemID, $stockItemAantal){
    $cart = getCart();                          // eerst de huidige cart ophalen

    if(array_key_exists($stockItemID, $cart)){  //controleren of $stockItemID(=key!) al in array staat
        $cart[$stockItemID] += $stockItemAantal;                   //zo ja:  aantal met 1 verhogen
    }else{
        $cart[$stockItemID] = $stockItemAantal;                    //zo nee: key toevoegen en aantal op 1 zetten.
    }

    saveCart($cart);                            // werk de "gedeelde" $_SESSION["cart"] bij met de bijgewerkte cart
}

function adjustCartProductQuantity($stockItemID, $aantal){
    $cart = getCart();
    if(array_key_exists($stockItemID, $cart)) {
        $cart[$stockItemID] = $aantal;
    }

    saveCart($cart);
}

function removeProductFromCart($stockItemID){
    $cart = getCart();
    unset($cart[$stockItemID]);

    saveCart($cart);
}

function remove1ProductFromCart($stockItemID){
    $cart = getCart();                          // eerst de huidige cart ophalen

    if($cart[$stockItemID] != 1){
        $cart[$stockItemID] = $cart[$stockItemID] - 1;
    }else{
        unset($cart[$stockItemID]);
    }

    saveCart($cart);
}

function saveCartPrice($totaalprijs){
    $_SESSION["cartPrice"] = $totaalprijs;
}

function getCartPrice(){
    $databaseConnection = connectToDatabase();
    $cart = getCart();
    $cartPrice = NULL;

    $Query = "
    SELECT StockItemID, (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice
    FROM stockitems SI 
    WHERE SI.StockItemID IN (" . implode(',' , array_keys($cart)) . ")";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

    foreach($Result as $ResultValue){
        $cartPrice = $cartPrice + ($cart[$ResultValue["StockItemID"]] * number_format((float)$ResultValue["SellPrice"], 2, ".", ""));
        $cartPrice = number_format((float)$cartPrice, 2, ".", "");
    }
    return $cartPrice;
}

function getCartTotalPrice(){
    return getCartPrice() + getVerzendkosten();
}

function getArtikelPrice($stockItemID){
    $databaseConnection = connectToDatabase();
    $cart = getCart();
    
    $Query = "SELECT (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice
    FROM stockitems SI 
    WHERE SI.StockItemID = (" . $stockItemID . ")";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

    $price = $cart[$stockItemID] * number_format((float)$Result[0]['SellPrice'], 2, ".", "") ;
    return number_format((float)$price, 2, ".", "");
}

function getCartPriceZonderBTW(){
    $databaseConnection = connectToDatabase();
    $cart = getCart();
    $cartPrice = NULL;

    $Query = "
    SELECT StockItemID, RecommendedRetailPrice
    FROM stockitems SI 
    WHERE SI.StockItemID IN (" . implode(',' , array_keys($cart)) . ")";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

    foreach($Result as $ResultValue){
        $cartPrice = $cartPrice + ($cart[$ResultValue["StockItemID"]] * number_format((float)$ResultValue["RecommendedRetailPrice"], 2, ".", ""));
        $cartPrice = number_format((float)$cartPrice, 2, ".", "");
    }
    return $cartPrice;
}

function getVerzendkosten(){
    $databaseConnection = connectToDatabase();
    $cart = getCart();
    $shipping_costs = 0;
    $totaleVerzendKosten = 0;
    
    foreach ($cart as $stockItemID => $quantity ) {
        $Query = "SELECT StockItemID, UnitPackageID
        FROM stockitems 
        WHERE StockItemID = ?";
        $Statement = mysqli_prepare($databaseConnection, $Query);
        mysqli_stmt_bind_param($Statement, "i", $stockItemID);
        mysqli_stmt_execute($Statement);
        $Result = mysqli_stmt_get_result($Statement);
        $Result = mysqli_fetch_all($Result, MYSQLI_ASSOC);

        $unitPackageID = $Result[0]['UnitPackageID'];
        
        switch($unitPackageID) {
            case 1:
                $base_cost = 6.95;
                $unit_threshold = 50;
                break;
            case 7:
                $base_cost = 6.95;
                $unit_threshold = 15;
                break;
            case 9:
                $base_cost= 6.95; // fixed price
                $unit_threshold = 1;
                break;
            case 10:
                $base_cost = 6.95;
                $unit_threshold = 100;
                break;
            default:
                $base_cost = 0;
                $unit_threshold = PHP_INT_MAX;
        }

        $extra_units = max(0, $quantity - $unit_threshold);
        $extra_costs = (int)($extra_units / $unit_threshold) * $base_cost;
        $shipping_costs += $base_cost + $extra_costs;
    }
    //return $Result;
    return $shipping_costs;

}