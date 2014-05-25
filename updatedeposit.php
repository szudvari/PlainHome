<?php
session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';

$id = $_GET['id'];
htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
if (isset($_SESSION["admin"]) && ($_SESSION["admin"] > 0))
{
$con = connectDb();
$table=getADeposit($id);
closeDb($con);
updatedeposit($table);

}
else 
{
    notLoggedIn();
}
htmlEnd();
