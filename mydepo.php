<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
htmlHead($website['title'], $house['name']);
webheader($_SESSION["admin"]);
if (isset($_SESSION['depositid'])){
$id = $_SESSION['depositid'];
}
else if (isset($_GET['depositid']) && ($_SESSION['admin']>0)) {
 $id = $_GET['depositid']; 
 echo '<div class="buttons btn-back">
	   <form action="deposits.php">
	   <button type="input" name="submit" value="Vissza" class="btn btn-success btn-icon"><i class="fa fa-arrow-circle-left"></i>Vissza</button>
	   </form>
	   </div>';
}
else {
     notLoggedIn();
     exit();
}
$con = connectDb();
getMyDepo($id);
closeDb($con);
htmlEnd();