<?php
session_start();
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
if (isset($_GET["logout"])) {
    session_unset();
    session_destroy();
    popUp("Sikeresen kijelentkezett!");
}
htmlEnd();