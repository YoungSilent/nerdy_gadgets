<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/paymentfuncties.php";


    ?> 
    <h1>Uw bestelling is voltooid.</h1>
    We hopen het zo snel mogelijk bij u te leveren.<br><br><br>
    <?php

$orderSummary = getOrderSummary($_SESSION["orderID"]);
foreach($orderSummary as $key => $value){
    ?>
    <h3><?php print($value["StockItemName"]); ?> </h3><br>
    <p><?php print($value["UnitPrice"]); ?> </p>
    <p><?php print($value["Quantity"]); ?> </p>
    <?php
}

include __DIR__ . "/footer.php";