<?php

session_start();

include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if (isset($_POST["from"]))
{
     $mailcheck = spamcheck($_POST["from"]);
    if ($mailcheck==FALSE) {
      echo "Invalid input";
    }
    $from = $_POST["from"]; // sender
}
else
{
    $con = connectDb();
    $user = getUserData($_SESSION['userid']);
    closeDb($con);
    $from = $user['email'];
}
$subject = $_POST["subject"];
$message = $_POST["message"];
// message lines should not exceed 70 characters (PHP rule), so wrap it
$message = wordwrap($message, 70);
// send mail
$mail = mail($house['infomail'], $subject, $message, "From: $from\n");
if ($mail){
header("Location: $_SERVER[HTTP_REFERER]?messagesent=1");
}
else {
    die("Hiba");
}
htmlEnd();
