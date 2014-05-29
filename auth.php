<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

$userdata['user'] = $_POST['user'];
$userdata['pass'] = encodePass($_POST['pass']);

$con = connectDb();

 htmlHead($website['title'], $house['name']);
 webheader($_SESSION);
if (stripos($_SERVER['HTTP_REFERER'], "/login.php"))
{
    $login = authUserDb($userdata, $con);
    if ($login)
    {
        $_SESSION['depositid'] = getUserDepositId($userdata);
        $_SESSION['admin'] = getUserRole($userdata);
        $_SESSION['userid'] = getUserId($userdata);
        $_SESSION['user'] = $userdata['user'];
        closeDb($con);
        header("Location: index.php");
		exit ();
    }
    else
    {
        popUp("Hibás felhasználónév és/vagy jelszó!");
        header("Refresh: 3; url={$_SERVER['HTTP_REFERER']}");
		exit();
    }
}
else if (stripos($_SERVER['HTTP_REFERER'], "/adminlogin.php"))
{
    $login = authAdminDb($userdata, $con);
    if ($login)
    {
        $_SESSION['admin'] = getAdminRole($userdata);
        $_SESSION['admin_userid'] = getAdminId($userdata);
        $_SESSION['admin_user'] = $userdata['user'];
        if (!isset($_SESSION['user'])){
            $_SESSION['user'] = $userdata['user'];
        }
        
        closeDb($con);
        header("Location: admin.php");
		exit();
    }
    else
    {
       
        popUp("Hibás felhasználónév és/vagy jelszó!");
        header("Refresh: 3; url={$_SERVER['HTTP_REFERER']}");
		exit();
    }
}
htmlEnd();





