<?php
session_start();
session_unset();
session_destroy();
include_once 'js.php';
include_once 'html.php';
include_once 'config.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);

popUp("Sikeresen kijelentkezett!");
htmlEnd();

//include 'index.php';

?>