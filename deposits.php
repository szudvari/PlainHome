<?php
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader();
$con= connectDb();
listDeposits();
closeDb($con);

addDeposit();

showContent ("newdeposit", "newdepo");

htmlEnd();

