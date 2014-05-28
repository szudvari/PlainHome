<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if (isset($_GET["succes"]))
{
    popUp("Új albetét sikeresen felvéve.");
}
if (isset($_GET["modified"]))
{
    popUp("Albetét sikeresen módosítva.");
}
if (isset($_SESSION["admin"]) && ($_SESSION["admin"] > 0))
{
    $con = connectDb();
    getAllDepo();
    closeDb($con);

    if (isset($_SESSION['admin']) && ($_SESSION['admin'] > 0))
    {
        addDeposit();

        showContent("newdeposit", "newdepo");
    }
}
else
{
    notLoggedIn();
}
htmlEnd();

