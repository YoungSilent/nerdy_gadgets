<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/paymentfuncties.php";

if (isset($_GET['order'])) {
    $order = $_GET['order'];
} else {
    $order = "";
}

if($order==1234){
    ?> 
    <h1>Uw bestelling is voltooid.</h1>
    <?php
}elseif($order==1235){
    ?> 
    <h1>Uw bestelling is mislukt.</h1>
    <?php
}


include __DIR__ . "/footer.php";