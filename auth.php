<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';

$userdata['user'] = $_POST['user'];
$userdata['pass'] = encodePass($_POST['pass']);

$con = connectDb();
$login = authUserDb($userdata, $con);
closeDb($con);

if ($login)
{
//    echo 'success';
    $con = connectDb();
    $_SESSION['admin'] = getUserRole($userdata);
    $_SESSION['user'] = $userdata['user'];
    $_SESSION['userid'] = getUserId($userdata);
    $_SESSION['depositid'] = getUserDepositId($userdata);
    closeDb($con);
    header("Location: index.php");
}
else
{
    echo '<p id="notloggedin">Hibás felhasználónév vagy jelszó.</p>';
    header("Refresh: 3; url={$_SERVER['HTTP_REFERER']}");
}

