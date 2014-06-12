<?php
session_start();

include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
$year = $_POST['year'];
$con=  connectDb();
getAllAccounts ($year);
closeDb($con);
}
 else {
    notLoggedIn();    
}

htmlEnd();

