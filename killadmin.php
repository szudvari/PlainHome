<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 1)
{
    $id = $_POST["uid"];
    if ($id != 1)
    {      
        $con = connectDb();
        killAdmin($id);
        closeDb($con);
        header("Location:listadmin.php?kill=1");
    }
    else {
     notLoggedIn2();
    }
}
else {
     notLoggedIn2();
}
htmlEnd();
ob_end_flush();