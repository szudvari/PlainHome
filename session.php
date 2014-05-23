<?php
session_start();
include_once 'html.php';
include_once 'config.php';
htmlHead($website['title'], $house['name']);
echo "{$_SESSION['admin']}<br>{$_SESSION['user']}<br>{$_SESSION['userid']}<br>{$_SESSION['depositid']}";
htmlEnd();


