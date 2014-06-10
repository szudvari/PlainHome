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
if (isset($_GET["password"]))
{
    popUp("Adminisztrátor jelszava sikeresen módosítva");
}
if (isset($_GET["kill"]))
{
    popUp("Adminisztrátor sikeresen törölve");
}
if (isset($_GET["new"]))
{
    popUp("Adminisztrátor sikeresen felvéve");
}
if ($_SESSION["admin"] > 0)
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
