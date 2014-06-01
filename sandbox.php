<?php
session_start();

include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);

$con = connectDb();
$ccost = getCcost($_SESSION['depositid']);
closeDb($con);

function round_up_to_nearest_n($int, $n) {
    return round($int / $n) * $n;
}
echo round_up_to_nearest_n(9676, 50);

//$ccost = (round($ccost/100))*100;
//
//echo $ccost;