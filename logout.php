<?php
session_start();
session_unset();
session_destroy();
include_once 'js.php';
include_once 'html.php';
include_once 'config.php';

htmlHead($website['title'], $house['name']);
loggedOut();
htmlEnd();
//echo '<p id="logout">Sikeresen kijelentkezett!</p>';
//include 'index.php';

?>