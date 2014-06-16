<?php
session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if (!isset($_SESSION['user']))
{
    popUp("<b>User bejelentkezés:</b><br> felhasználónév: webariel <br> jelszó: webariel <br><br><b>Admin belépéshez kattintson <a href=\"adminlogin.php\"> IDE </a></b>");
    loginUser();
}
else
{
     echo "<p class=\"lead\">Már bejelentkeztél mint {$_SESSION['user']}.</p>";
}
htmlEnd();