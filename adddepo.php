<?php
include_once 'functions.php';
include_once 'db.php';
include_once 'config.php';

$deposit['floor'] = $_POST['floor'];
$deposit['door'] = $_POST['door'];
$deposit['area'] = $_POST['area'];
$deposit['residents'] = $_POST['residents'];
$deposit['note'] = $_POST['note'];
        
$con= connectDb();
insertDepoDb($deposit, $con);
closeDb($con);

header("Location:{$_SERVER['HTTP_REFERER']}");
