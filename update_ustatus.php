<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
    $id = $_GET["uid"];
    $status = $_GET["status"];
    $con = connectDb();
    changeUserSatus($id, $status);
    closeDb($con);
    header("Location:allresidents.php?active=1");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();