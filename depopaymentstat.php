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
    $id = $_POST['depoid'];
    echo "<div class='content'>";
    $con = connectDb();
    getDepositPayments($year, $id);
    closeDb($con);

} else {
    notLoggedIn();
}

htmlEnd();
