<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/paymentfuncties.php";
print_r(getcart());
createOrderLines(73603);