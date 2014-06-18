<?php

include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
$con = connectDb();
for ($i=1; $i<66; $i++) {
    $sql = "SELECT SUM(`amount`) as amount FROM `payment` WHERE `deposit_id`=$i AND (`account_date` between '2013-01-01' AND '2013-12-31')";
     
    $result = mysql_query($sql);
    if (!$result) {
        echo "select amount $i hiba -".mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $amount = $row['amount'];
    }
    $sql = "SELECT `opening_balance` FROM `deposit_balance` WHERE `deposit_id`=$i AND `year`=2013";
    $result = mysql_query($sql);
    if (!$result) {
        echo "select balance $i hiba -".mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $balance = $row['opening_balance'];
    }
    $actual = $balance+$amount;
    $sql="UPDATE `{$db['name']}`.`deposit_balance` SET `actual_balance` = $actual WHERE `deposit_balance`.`deposit_id` = $i;";
    $result = mysql_query($sql);
    if (!$result) {
        echo "update balance $i hiba -".mysql_errno() . ": " . mysql_error();
        exit;
    }
    else {
        echo "$i done<br>";
    }
}
closeDb($con);