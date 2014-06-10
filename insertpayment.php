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
    $deposit['id'] = $_POST['did'];
    $deposit['payment'] = str_replace(".", "-", $_POST['payment']);
    $deposit['account_date'] = $_POST['account_date'];
    
//print_r($deposit);
    $con = connectDb();
    insertPayment ($deposit, $_SESSION['user']);
    closeDb($con);

    header("Location:deposits.php?payed=1");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush(); 

