<?php
session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
 $con = connectDb();   
    $pass = $_POST['pass'];
    $pass2 = $_POST['pass2'];
    $userdata['email'] = $_POST['email'];
    $userdata['username'] = $_POST['username'];
    $userdata['password'] = encodePass($pass);

    if ($pass == $pass2)
    {
        insertTable("admin", $userdata, $con);
        closeDb($con);
        echo '<p id="logout"> Felhasználó felvéve.</p>';
        //header("Refresh: 2; url=useradmin.php");
        
    }
    else
    {
        closeDb($con);
        echo "A jelszó nem egyezik!";
        header("Refresh: 2; url={$_SERVER['HTTP_REFERER']}");
    }
}
else {
     notLoggedIn();
}
    htmlEnd();

?>