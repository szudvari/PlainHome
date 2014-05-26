<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
$con = connectDb();
getAllDepo();
closeDb($con);
htmlEnd();
