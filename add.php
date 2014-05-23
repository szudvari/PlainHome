<?php

include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';

 $con = connectDb();   
    $pass = $_POST['pass'];
    $pass2 = $_POST['pass2'];
    $floor = $_POST['floor'];
    $door = $_POST['door'];
    $userdata['firstname'] = $_POST['firstname'];
    $userdata['lastname'] = $_POST['lastname'];
    $userdata['email'] = $_POST['email'];
    $userdata['username'] = $_POST['username'];
    $userdata['depositid'] = getDepositId($floor, $door);
    $userdata['password'] = encodePass($pass);

    if ($pass == $pass2)
    {
        insertTable("residents", $userdata, $con);
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
    
    htmlEnd();

?>