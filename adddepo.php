<?php
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';

$deposit['floor'] = $_POST['floor'];
$deposit['door'] = $_POST['door'];
$deposit['area'] = $_POST['area'];
$deposit['residents'] = $_POST['residents'];
$deposit['resident_name'] = $_POST['note'];
$deposit['garage_area'] = $_POST['garage_area'];
$deposit['area_ratio'] = str_replace(",",".",$_POST['area_ratio']);
$deposit['garage_area_ratio'] = str_replace(",",".",$_POST['garage_area_ratio']);
$deposit['watermeter'] = $_POST['watermeter'];

//print_r ($deposit);        
$con= connectDb();
insertDepoDb($deposit, $con);
closeDb($con);

header("Location:deposits.php?succes=1");
