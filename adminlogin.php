<?php
session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
if (!isset($_SESSION['admin']) || (($_SESSION['admin']) == 0))
{
    loginUser();
}
else
{
     echo "<p class=\"lead\">Már bejelentkeztél mint {$_SESSION['user']}.</p>";
}
htmlEnd();