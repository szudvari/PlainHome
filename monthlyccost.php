<?php

include_once 'config.php';
include_once 'db.php';

$year = date('Y');
$month = date('m');
//$month = 5;
$sql = "SELECT `id` FROM `deposits` ";
$con = connectDb();
$result = mysql_query($sql);

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

closeDb($con);
echo "done";
