<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ((stripos($_SERVER['HTTP_REFERER'], "/mydepo.php")) && (!stripos($_SERVER['HTTP_REFERER'], "payed=1"))){
$url = $_SERVER['HTTP_REFERER']."&payed=1";
}
else if (stripos($_SERVER['HTTP_REFERER'], "payed=1")) {
    $url = $_SERVER['HTTP_REFERER'];
}
    else {
    $url = $_SERVER['HTTP_REFERER']."?payed=1";
}
if ($_SESSION["admin"] > 0)
{
    $deposit['id'] = $_POST['did'];
    $deposit['payment'] = $_POST['payment'];
    $deposit['account_date'] = str_replace(".", "-", $_POST['account_date']);
    
//print_r($deposit);
    $con = connectDb();
    insertPayment ($deposit, $_SESSION['user']);
    closeDb($con);

    header("Location:$url");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush(); 

