<?php
session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if ($_SESSION["admin"] > 0)
{
$base['ccost'] = $_POST['ccost'];
$base['grabage'] = $_POST['grabage'];

$con = connectDb();
updatebase($base);
closeDb($con);
header("Location:basedata.php?update=1");
}
else 
{
    notLoggedIn();
}
htmlEnd();
