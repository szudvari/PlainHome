<?php
session_start();

include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);

    $id = $_SESSION['userid'];
    $con = connectDb();
    $user = getUserData($id);
    $ccost = getCcost($_SESSION['depositid']);
    $closing_balance = getCurrentBalance($_SESSION['depositid']);
    getActualBalance ($ccost, $closing_balance);
    $balance = 0; // JAVÍTANI!

