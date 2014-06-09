<?php
session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if (isset($_GET["del"]))
{
    popUp("File törölve");
}

if (isset($_GET["new"]))
{
    popUp("Új file sikeresen feltöltve");
}

if ($_SESSION["admin"] > 0)
{
documents();
uploadFile();
}
else
{
    notLoggedIn();
}
validateForm();
htmlEnd();