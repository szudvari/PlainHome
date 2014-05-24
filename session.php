<?php

session_start();
include_once 'html.php';
include_once 'config.php';
htmlHead($website['title'], $house['name']);
letterHead();
if (isset($_SESSION['user']))
{
    print_r($_SESSION);
}
else
{
    echo "No session";
}
htmlEnd();


