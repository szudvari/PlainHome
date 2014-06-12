<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0) {
    $id = $_GET["id"];
    $floor = $_GET["floor"];
    $door = $_GET["door"];
    $payment['amount'] = $_GET["amount"];
    $con = connectDb();
    $payment['oldid'] = getDepositId($floor, $door);
     getPaymentData ($id);
     reAccountPayment($id, $payment);
}
 else {
    notLoggedIn();    
}
validateForm();
htmlEnd();
ob_end_flush();