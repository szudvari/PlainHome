<?php
session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
addAdmin();
htmlEnd();