<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';
include_once 'functions.php';

ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if (($_SESSION["admin"] > 0) || ($_SESSION["userid"] == $_GET["uid"])) {

    if (isset($_GET["uid"])) {
        $id = $_GET["uid"];
    } else {
        $id = $_POST["user_id"];
    }


    @$pass1 = $_POST["pass1"];
    @$pass2 = $_POST["pass2"];
    //if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (($pass1 == "") || ($pass2 == "")) {
            echo '<p class="warning">Kérem, töltse ki a jelszó mezőket!</p>';
        } else {
            if ($pass1 != $pass2) {
                echo '<p class="warning">A két jelszó nem egyezik!</p>';
            } else {
                $user['pass'] = $pass1;
                $password = encodePass($pass1);
                $con = connectDb();
                changeUserPassword($id, $password, $con);
                closeDb($con);
                sendMessageAfterUpdatePassword($user);
                if ($_SESSION["admin"] > 0) {
                    header("Location:allresidents.php?password=1");
                    exit();
                } else {
                    header("Location:mydepo.php?password=1");
                    exit();
                }
            }
        //}
        echo "</div>";
    }
} else {
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
