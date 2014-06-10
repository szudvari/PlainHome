<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if (($_SESSION["admin"] > 0) || ($_SESSION["userid"] == $_POST["id"])) {
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];
    $id = $_POST["id"];

    if ($pass1 != $pass2) {
        echo '<p class="warning">A két jelszó nem egyezik!</p>';
        header("Refresh: 2; url=mydepo.php");
        exit();
    } else {
        $password = encodePass($pass1);
        $con = connectDb();
        changeUserPassword($id, $password, $con);
        closeDb($con);
        if ($_SESSION["admin"] > 0) {
            header("Location:allresidents.php?password=1");
            exit();
        } else {
            header("Location:mydepo.php?password=1");
            exit();
        }
    }

    echo "</div>";
} else {
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
