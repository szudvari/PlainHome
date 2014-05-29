<?php
session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

$id = $_POST["id"];
$pass1 = $_POST["pass1"];
$pass2 = $_POST["pass2"];
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
    if ($pass1 != $pass2)
    {
        echo '<p id="notloggedin">A két jelszó nem egyezik!</p>';
    }
    else
    {
        $password = encodePass($pass1);
        $con = connectDb();
        changeUserPassword($id, $password, $con);
        closeDb($con);
        header("Location:allresidents.php?password=1");
    }
htmlEnd();
