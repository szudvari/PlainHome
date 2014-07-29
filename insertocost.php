<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ((stripos($_SERVER['HTTP_REFERER'], "/mydepo.php")) && (!stripos($_SERVER['HTTP_REFERER'], "saved=1"))){
$url = $_SERVER['HTTP_REFERER']."&saved=1";
}
else if (stripos($_SERVER['HTTP_REFERER'], "saved=1")) {
    $url = $_SERVER['HTTP_REFERER'];
}
    else {
    $url = $_SERVER['HTTP_REFERER']."?saved=1";
}
if ($_SESSION["admin"] > 0)
{
    $deposit['deposit_id'] = $_POST['did'];
    $deposit['ocost'] = $_POST['cost'];
    $deposit['title'] = $_POST['title'];
    $date = $_POST['date'];
    $deposit['year'] = substr($date, 0, 4);
    $deposit['month'] = substr($date, 5, 2);
    $deposit['day'] = substr($date, 8, 2);
    
    //print_r ($deposit);
    

    $con = connectDb();
    insertOcost ($deposit);
    closeDb($con);

    header("Location:$url");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush(); 