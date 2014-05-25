<?php

session_start();
include_once 'html.php';
include_once 'config.php';
htmlHead($website['title'], $house['name']);
webheader();
echo "<div class=\"content\">";
if (isset($_SESSION['user']))
{
    print_r($_SESSION);
}
else
{
    echo "No session";
}
echo "</div>";
htmlEnd();


