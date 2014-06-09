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
    $id = $_GET["id"];
    $act = $_GET["act"];
    $con = connectDb();
    changeMsgStatus($id, $act);
    closeDb($con);
    header("Location:board_admin.php?change=1");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();