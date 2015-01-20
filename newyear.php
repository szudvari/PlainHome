<?php

session_start();
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';
include_once 'html.php';
include_once 'js.php';
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $con = connectDb();
    $lastyear = lastYear();
    closeDb($con);

    if ((($year == $lastyear && $month == 12) && $day > ($house['payment_day'] + 4)) || ($year > $lastyear))
    {
        $con = connectDb();
        startNewYear($lastyear);
        closeDb($con);
        //header("Location:admin.php");
    }
     else
    {
    echo '<div class="content">Új év nyitására csak az előző év utolsó befizetési dátuma után 5 nappal van lehetőség.</div>'; 
    }
}
else
{
    notLoggedIn();
}

htmlEnd();
ob_flush();
