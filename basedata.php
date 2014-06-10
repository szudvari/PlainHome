<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if (isset($_GET["update"]))
{
    popUp("Díjak sikeresen módosítva");
}
if ($_SESSION["admin"] > 0)
{
    $con = connectDb();
    $data=getBaseData();
    closeDb($con);

    if ($_SESSION['admin'] > 0)
    {
        updateBaseData($data);

        showContent("changedata", "updatedata");
    }
}
else
{
    notLoggedIn();
}
htmlEnd();

