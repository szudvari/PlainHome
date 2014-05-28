<?php
include_once 'config.php';
include_once 'db.php';

$id = $_GET["uid"];
$status = $_GET["status"];
            $con = connectDb();
            changeUserSatus($id, $status);
            closeDb($con);
            header("Location:allresidents.php?active=1");