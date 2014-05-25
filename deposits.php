<?php
session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader();
$con= connectDb();
listDeposits();
closeDb($con);

if (isset($_SESSION['admin']) && ($_SESSION['admin']>0)) {
addDeposit();

showContent ("newdeposit", "newdepo");
}

htmlEnd();

