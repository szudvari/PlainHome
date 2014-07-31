<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0) {
    $id = $_POST["uid"];
    if ($id != 1) {
        $pass1 = $_POST["pass1"];
        $pass2 = $_POST["pass2"];

        if (($pass1 == "") || ($pass2 == "")) {
            echo '<p class="warning">Kérem, töltse ki a jelszó mezőket!</p>';
        } else {
            if ($pass1 != $pass2) {
                echo '<p id="notloggedin">A két jelszó nem egyezik!</p>';
            } else {
                $password = encodePass($pass1);
                $con = connectDb();
                changeAdminPassword($id, $password, $con);
                closeDb($con);
                header("Location:listadmin.php?password=1");
            }
        }
    } else {
        notLoggedIn2();
    }
} else {
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
