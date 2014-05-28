<?php
include_once 'config.php';
include_once 'db.php';

$id = $_GET["uid"];
$status = $_GET["status"];
            $con = connectDb();
            changeAdminSatus($id, $status);
            closeDb($con);
            header("Location:allresidents.php?admin=1");