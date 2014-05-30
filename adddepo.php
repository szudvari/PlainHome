<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';


htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
    $deposit['floor'] = $_POST['floor'];
    $deposit['door'] = $_POST['door'];
    $deposit['area'] = str_replace(",", ".", $_POST['area']);
    $deposit['residents'] = $_POST['residents'];
    $deposit['resident_name'] = $_POST['note'];
    $deposit['area_ratio'] = str_replace(",", ".", $_POST['area_ratio']);

//print_r($deposit);
    $con = connectDb();
    insertDepoDb($deposit, $con);
    closeDb($con);

    header("Location:deposits.php?succes=1");
}
else
{
    notLoggedIn();
}
htmlEnd();
