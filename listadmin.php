<?php
session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if (isset($_GET["admin"]))
{
    popUp("Felhasználó admin rangja sikeresen módosítva");
}
if ($_SESSION["admin"] > 1)
{
    $con = connectDb();
    listAdmins();
    closeDb($con);
    addAdmin();
    showContent("addadmin", "newadmin");
}
else
{
    notLoggedIn();
}
htmlEnd();
