<?php

session_start();

include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if ($_SESSION["admin"] > 0) {
    $year = $_POST['year'];
    $floor = $_POST['floor'];
    $door = $_POST['door'];
    if ($floor == 0 && $door!=0) {
        $floor = "fsz.";
    }
    $con = connectDb();
    $id = getDepositId($floor, $door);
    getDepositCcost($id, $year);
    closeDb($con);
} else {
    notLoggedIn();
}

htmlEnd();