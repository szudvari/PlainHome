<?php
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
$con= connectDb();
listDeposits();
closeDb($con);
addDeposit();
htmlEnd();

