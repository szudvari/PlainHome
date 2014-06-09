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
    $file['name']=$_FILES["file"]["name"];
    $file['shortname']=$_POST["shortname"];
    $file['description']=$_POST["description"];
    $allowedExts = array("gif", "jpeg", "jpg", "png", "pdf");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);

    if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "application/pdf") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 20000000) && in_array($extension, $allowedExts))
    {
        if ($_FILES["file"]["error"] > 0)
        {
            echo "<div class='content'>Return Code: " . $_FILES["file"]["error"] . "<br></div>";
        }
        else
        {
            if (file_exists("documents/" . $_FILES["file"]["name"]))
            {
                echo "<div class='content'>".$_FILES["file"]["name"] . " már létezik.</div> ";
            }
            else
            {
                move_uploaded_file($_FILES["file"]["tmp_name"], "documents/" . $_FILES["file"]["name"]);
                $con = connectDb();
                insertTable("documents", $file, $con);
                closeDb($con);
                header("Location:documents.php?new=1");
            }
        }
    }
    else
    {
        echo "<div class='content'>Nem megfelelő formátumú, vagy méretű file</div>";
        exit();
    }
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
