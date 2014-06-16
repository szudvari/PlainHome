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
    $pass = $_POST['pass'];
    $pass2 = $_POST['pass2'];
    $floor = $_POST['floor'];
    $door = $_POST['door'];
    $userdata['firstname'] = $_POST['firstname'];
    $userdata['lastname'] = $_POST['lastname'];
    $userdata['email'] = $_POST['email'];
    $userdata['username'] = $_POST['username'];
    $userdata['depositid'] = getDepositId($floor, $door);
    $userdata['password'] = encodePass($pass);
    $user['pass'] = $pass;
    $user['firstname'] = $userdata['firstname'];
    $user['lastname'] = $userdata['lastname'];
    $user['username'] = $userdata['username'];
    $user['email'] = $userdata['email'];

    if ($pass == $pass2)
    {
        insertTable("residents", $userdata, $con);
        closeDb($con);
        sendMessageToNewUser($user);
        header("Location: allresidents.php?newuser=1");
        //header("Refresh: 2; url=useradmin.php");
    }
    else
    {
        closeDb($con);
        echo "A jelszó nem egyezik!";
        header("Refresh: 2; url={$_SERVER['HTTP_REFERER']}");
    }
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
