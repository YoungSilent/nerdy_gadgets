<?php
include "cartfuncties.php";
$cart = getCart();
unset($cart[""]);
saveCart($cart);