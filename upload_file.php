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
    $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);

    if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "application/pdf") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 20000000) && in_array($extension, $allowedExts))
    {
        if ($_FILES["file"]["error"] > 0)
        {
            echo "<div id='content'>Return Code: " . $_FILES["file"]["error"] . "<br></div>";
        }
        else
        {
            if (file_exists("documents/" . $_FILES["file"]["name"]))
            {
                echo "<div id='content'>".$_FILES["file"]["name"] . " már létezik.</div> ";
            }
            else
            {
                move_uploaded_file($_FILES["file"]["tmp_name"], "documents/" . $_FILES["file"]["name"]);
                header("Location:documents.php?new=1");
            }
        }
    }
    else
    {
        echo "<div id='content'>Nem megfelelő formátumú, vagy méretű file</div>";
        exit();
    }
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
