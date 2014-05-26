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
	echo '<div class="buttons btn-back">
		   <form action="deposits.php">
		   <button type="input" class="btn btn-success btn-icon"><i class="fa fa-arrow-circle-left"></i>Vissza</button>
		   </form>
		   </div>';
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
