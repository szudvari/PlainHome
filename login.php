<?php
session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
htmlHead($website['title'], $house['name']);
if (!isset($_SESSION['login']))
{
    loginUser();
}
else
{
     echo "<p>Már bejelentkeztél mint {$_SESSION['user']}.</p>";
}
     
htmlEnd();