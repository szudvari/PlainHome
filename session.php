<?php

session_start();
include_once 'html.php';
include_once 'config.php';
htmlHead($website['title'], $house['name']);
letterHead();
if (isset($_SESSION['user']))
{
    echo "{$_SESSION['admin']}<br>{$_SESSION['user']}<br>{$_SESSION['userid']}<br>{$_SESSION['depositid']}";
}
else
{
    echo "No session";
}
copyRight();
htmlEnd();


