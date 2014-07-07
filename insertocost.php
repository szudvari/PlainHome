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
    $deposit['deposit_id'] = $_POST['did'];
    $deposit['ocost'] = $_POST['cost'];
    $deposit['title'] = $_POST['title'];
    $date = $_POST['date'];
    $deposit['year'] = substr($date, 0, 4);
    $deposit['month'] = substr($date, 5, 2);
    $deposit['day'] = substr($date, 8, 2);
    
    print_r ($deposit);
    

    $con = connectDb();
    insertOcost ($deposit);
    closeDb($con);

    //header("Location:deposits.php?saved=1");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush(); 