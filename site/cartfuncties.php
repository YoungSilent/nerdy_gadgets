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

function addProductToCart($stockItemID){
    $cart = getCart();                          // eerst de huidige cart ophalen

    if(array_key_exists($stockItemID, $cart)){  //controleren of $stockItemID(=key!) al in array staat
        $cart[$stockItemID] += 1;                   //zo ja:  aantal met 1 verhogen
    }else{
        $cart[$stockItemID] = 1;                    //zo nee: key toevoegen en aantal op 1 zetten.
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

function getCartTotalPrice($verzendkosten){
    return getCartPrice() + $verzendkosten;
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