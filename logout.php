<?php
session_start();
session_unset();
session_destroy();
include_once 'js.php';
include_once 'html.php';
include_once 'config.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
echo '<div id="logout">Sikeresen kijelentkezett!</div>';
popUp("logout");
htmlEnd();

//include 'index.php';

?>