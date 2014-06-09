<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if ($_SESSION["admin"] > 0)
{
  $msg['title']=$_POST['title'];  
  $msg['text']=$_POST['text'];  
  if (isset($_POST['valid_till'])) {
      $msg['valid_till'] = $_POST['valid_till'];
  }
  $con = connectDb();
  insertBoardMessage($msg);
  closeDb($con);
  header("Location:board_admin.php?new=1");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
