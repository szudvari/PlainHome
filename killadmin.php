<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 1)
{
    $id = $_GET["uid"];
    if ($id != 1)
    {
        $con = connectDb();
        $user = getAdminData($id);
        $action = $_SERVER["PHP_SELF"] . "?uid=$id";
        closeDb($con);
        echo <<<EOT
   <div class="content">
   <h3 class="primary"><i class="fa fa-key"></i> Admin törlése</h3>
   <form method="post" action="$action"> 
   <table id="responsiveTable" class="large-only" cellspacing="0">
    <tr align="left" class="primary">
    <th>Id</név>
    <th>Username</th>
    <th>e-mail cím</th>
    <th>Admin törlése</th>    
    </tr>
    <tbody>
    <tr>
    <td>{$user['id']}</td>
    <td>{$user['username']}</td>
    <td>{$user['email']}</td>
    
    <td><button type="input" name="submit" value="Töröl" class="btn btn-danger btn-icon"><i class="fa fa-times"></i>Töröl</button><span class="pwarning">Figyelem nem visszavonható!</span</td>
    </tr>
    </tbody>
    </table>
    </form>
    <div class="buttons">
    <a href="listadmin.php"><button type="input" class="btn btn-success btn-icon"><i class="fa fa-times"></i>Mégsem</button></a>
    </div>
    </div>
EOT;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $con = connectDb();
        killAdmin($id);
        closeDb($con);
        header("Location:listadmin.php?kill=1");
    }
    }
    else {
     notLoggedIn2();
    }
}
else {
     notLoggedIn2();
}
htmlEnd();