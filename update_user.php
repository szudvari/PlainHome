<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';

ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
    $con = connectDb();
    $userdata['id'] = $_POST['user_id'];
    $userdata['firstname'] = $_POST['firstname'];
    $userdata['lastname'] = $_POST['lastname'];
    $userdata['email'] = $_POST['email'];
    $userdata['phone'] = $_POST['phone'];
    
    updateUserData($userdata);
    closeDb($con);
    header("Location: allresidents.php?changeuser=1");
    
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
