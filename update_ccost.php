<?php
session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if ($_SESSION["admin"] > 0)
{
$ccost['id'] = $_POST['id'];
$ccost['ccost'] = $_POST['ccost'];

$con = connectDb();
updateCcostDb($ccost);
$id = getDepositIdByCcost($ccost['id']);
$year = getCcostYear($ccost['id']);
updateDepoBalance($year, $id);
closeDb($con);
header("Location:stat.php?update=1");
}
else 
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();