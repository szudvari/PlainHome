<?php

session_start();

include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
$url = "allresidents.php?messagesent=1"; 

$subject = $_POST["subject"];
$message = $_POST["comment"];
$email = $_POST["email"];
// message lines should not exceed 70 characters (PHP rule), so wrap it
$message = wordwrap($message, 70);
// send mail
$mail = mail($email, $subject, $message, "From: {$house['infomail']}\n");
//echo $email;
//echo "<br>";
//echo $subject;
//echo "<br>";
//echo $message;
//echo "<br>";
//echo "From: {$house['infomail']}\n";
header("Location:$url");
}
else
{
    notLoggedIn();
}
htmlEnd();
ob_end_flush();