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
}
else {
     notLoggedIn();
     exit();
}
$con = connectDb();
getMyDepo($id);
closeDb($con);
htmlEnd();

