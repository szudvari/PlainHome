<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
    $data['id'] = $_POST['id']; //payment id
    $data['oldid'] = $_POST['oldid']; //old depoid
    $data['amount'] = $_POST['amount']; //amount of payment
    $floor= $_POST['floor'];
    $door = $_POST['door'];
     if ($floor == 0 && $door != 0) {
        $floor = "fsz.";
    }
    
    
    $con = connectDb();
    $data['did']= getDepositId($floor, $door); //new depoid
    changeBalanceByReaccount($data);
    changeDepoOnPayment($data);
    closeDb($con);

    header("Location:stat.php");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush(); 
