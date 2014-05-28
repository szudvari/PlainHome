<?php
session_start();
include_once 'config.php';
include_once 'db.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
$id = $_GET["uid"];
$status = $_GET["status"];
$con = connectDb();
changeUserSatus($id, $status);
closeDb($con);
header("Location:allresidents.php?active=1");
htmlEnd();
