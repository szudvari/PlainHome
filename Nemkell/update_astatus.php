<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
    $id = $_GET["uid"];
    $status = $_GET["status"];
    $con = connectDb();
    changeAdminSatus($id, $status);
    closeDb($con);
    header("Location:allresidents.php?admin=1");
}
else
{
    notLoggedIn();
}
htmlEnd();
