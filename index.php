<?php

session_start();
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';
htmlHead($website['title'], $house['name']);
if (isset($_GET["logout"]))
{
    session_unset();
    session_destroy();
    $_SESSION["admin"] = NULL;
    popUp("Sikeresen kijelentkezett!");
}
webheader($_SESSION["admin"]);
if (isset($_SERVER['HTTP_REFERER']))
{
    if (stripos($_SERVER['HTTP_REFERER'], "/login.php") && isset($_SERVER['user']))
    {
        popUp("Sikeresen bejelentkezett mint \"" . $_SESSION['user'] . "\"!");
    }
}
htmlEnd();
