<?php
if(session_status() === PHP_SESSION_NONE) session_start();

function saveCustomerID(){
        $_SESSION["customerID"] = 100;
}

function getCustomerID(){
    return $_SESSION["customerID"];
}