<?php
session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if (!isset($_SESSION['admin']) || (($_SESSION['admin']) == 0))
{
    popUp("<b>Admin bejelentkezés:</b><br> felhasználónév: kozoskepviselo <br> jelszó: webariel");
    loginUser();
}
else
{
     echo "<p class=\"lead\">Már bejelentkeztél mint {$_SESSION['user']}.</p>";
}
htmlEnd();