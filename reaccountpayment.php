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
    $id = $_POST['id'];
    $floor= $_POST['floor'];
     if ($floor == 0) {
        $floor = "fsz.";
    }
    $door = $_POST['door'];
    
//print_r($deposit);
    $con = connectDb();
    $did= getDepositId($floor, $door);
    changeDepoOnPayment($id, $did);
    closeDb($con);

    header("Location:stat.php");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush(); 
