<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if (isset($_GET['messagesent'])) {
     popUp("Üzenet sikeresen elküldve");
}
if (isset($_GET["password"]))
{
    popUp("Jelszavát sikeresen megváltoztatta!");
}
if ($_SESSION['admin'] > 0) {
    echo '<div class="buttons btn-back">
	   <form action="deposits.php">
	   <button type="input" name="submit" value="Vissza" class="btn btn-success btn-icon"><i class="fa fa-arrow-circle-left"></i>Vissza</button>
	   </form>
	   </div>';
}
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
hideArea("mydepobutton");
hideArea("ccostbutton");
if (isset($_SESSION['depositid'])){
sendMessage();
showContent("message", "messagebutton");
changePassword($_SESSION['userid']);
}
htmlEnd();