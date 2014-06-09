<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if (isset($_GET["change"]))
{
    popUp("Hír státusza megváltozott");
}

if (isset($_GET["new"]))
{
    popUp("Új hír sikeresen feltöltve");
}

if ($_SESSION["admin"] > 0)
{
    $con = connectDb();
    getAllBoardMessages();
    closeDb($con);
    newBoardMessage();
}
else
{
    notLoggedIn();
}
validateForm();
htmlEnd();
