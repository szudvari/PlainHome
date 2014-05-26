<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';

$id = $_SESSION['depositid'];
htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
$con = connectDb();
getMyDepo($id);
closeDb($con);
htmlEnd();

