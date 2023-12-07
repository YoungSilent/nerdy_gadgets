<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/paymentfuncties.php";

if (isset($_GET['status'])) {
    $status = $_GET['status'];
} else {
    $status = "";
}
    ?> 
    <h1>Uw bestelling is mislukt.</h1>
    <?php

include __DIR__ . "/footer.php";