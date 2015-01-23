<?php

/*
 * Design and programming by WebAriel
 * Visit http://webariel.hu
 * E-mail: info@webariel.hu
 */
session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if ($_SESSION["admin"] > 0) {
    $year = date('Y');
    $con = connectDb();
    updateAllBalance($year);
    closeDb($con);
    echo "Az egyenlegeket sikeresen módosítottam.";
    
} else {
    notLoggedIn();
}

htmlEnd();
ob_flush();
