<?php

include_once 'config.php';
include_once 'db.php';

$year = date('Y');
$month = date('m');
$con = connectDb();
$sql = "SELECT MAX(`month`) as month FROM `ccost` WHERE `year`=$year";
$result = mysql_query($sql);
if (!$result)
{
    echo mysql_errno() . ": " . mysql_error();
    exit;
}
while ($row = mysql_fetch_assoc($result)) {
    $lastmonth = $row['month'];
}
if ($lastmonth >= $month)
{
    die("Az adott honapra mar rogzitesre kerult a kozoskoltseg");
}
else
{
    $sql = "SELECT `id` FROM `deposits` ";
    $result = mysql_query($sql);
    if (!$result)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $deposit = array();
    while ($row = mysql_fetch_assoc($result)) {
        $deposit[] = $row;
    }

//print_r($deposit);
    foreach ($deposit as $row) {
        $ccost = getCcost($row['id']);
        $sql = "INSERT into `ccost` (`deposit_id`, `year`, `month`, `ccost`) "
                . "VALUES ('{$row['id']}', '$year', '$month', '$ccost')";
        //echo $sql;
        mysql_query($sql);
    }
    updateAllBalance($year);
}

closeDb($con);
//echo "done";
