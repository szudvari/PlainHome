<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if ($_SESSION["admin"] > 0)
{
    $file = "./documents/" . $_GET['file'];
    $filename = $_GET['file'];
    $con = connectDb();
    deleteFileFromDb($filename);
    closeDb($con);
    deleteFile($file);
    header("location:documents.php?del=1");
}
else
{
    notLoggedIn();
}

htmlEnd();
ob_end_flush();

