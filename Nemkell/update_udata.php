<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';
include_once 'js.php';
include_once 'functions.php';

ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);

if ($_SESSION["admin"] > 0) {
    $id = $_GET["uid"];
    $con = connectDb();
    $user = getUserData($id);
    closeDb($con);
    echo <<<EOT
   <div class="content">
   <h3 class="primary"><i class="fa fa-refresh"></i> Jelszó módosítása</h3>
   <table id="responsiveTable" class="large-only" cellspacing="0">
    <tr align="left" class="primary">
    <th>Id</név>
    <th>Vezetéknév</th>
    <th>Keresztnév</th>
    <th>e-mail cím</th>
    <th>Telefonszám</th>
    <th>Felhasználónév</th>
    <th>Emelet</név>
    <th>Ajtó</név>
     
    </tr>
    <tbody>
    <tr>
    <td>{$user['id']}</td>
    <td>{$user['firstname']}</td>
    <td>{$user['lastname']}</td>
    <td>{$user['email']}</td>
    <td>{$user['phone']}</td>
    <td>{$user['username']}</td>
    <td>{$user['floor']}</td>
    <td>{$user['door']}</td>
    </tr>
    </tbody>
    </table>

EOT;
    
    updateUser($user);

} else {
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
