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
$deposit['area_ratio'] = $_POST['area_ratio'];
$deposit['garage_area_ratio'] = $_POST['garage_area_ratio'];
$deposit['watermeter'] = $_POST['watermeter'];

        
$con= connectDb();
insertDepoDb($deposit, $con);
closeDb($con);

header("Location:deposits.php?succes=1");
