<?php
session_start();

include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);

$con=  connectDb();
echo <<<EOT
<form method="get">
EOT;
depostisToDropDown();
echo "</form>";

closeDb($con);


//$year=2013;
// $nextyear  = date("Y-m-d",mktime(0, 0, 0, 1, 1,$year+1));
// $lastyear  = date("Y-m-d",mktime(0, 0, 0, 12, 31,$year-1));
// echo $nextyear;
// echo "<br>";
// echo $lastyear;
echo "Today is " . date("Y.m.d") . "<br>";
htmlEnd();

