<?php
session_start();
include_once 'config.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
htmlEnd();