<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
if (isset($_GET["logout"]))
{
    session_unset();
    session_destroy();
    $_SESSION["admin"] = NULL;
    popUp("Sikeresen kijelentkezett!");
}
webheader($_SESSION);

if (isset($_GET["login"]))
{
    popUp("Sikeresen bejelentkezett mint \"" . $_SESSION['user'] . "\"!");
}


if (!isset($_SESSION['user']))
{
    welcomeIndexNoUser();
    sendMessageNoUser();
}
else if (isset($_SESSION['depositid']))
{
    $id = $_SESSION['userid'];
    $con = connectDb();
    $user = getUserData($id);
    $ccost = getCcost($_SESSION['depositid']);
    welcomeIndexUser($user, $ccost);
    getMyDepo($_SESSION['depositid']);
    showContent("mydepo", "mydepobutton");
    showContent("ccost", "ccostbutton");
    closeDb($con);
    echo "<hr>";
    sendMessage();
    showContent("message", "messagebutton");
    echo "<hr>";
    changePassword($_SESSION['userid']);
}
htmlEnd();
?>
