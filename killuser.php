<?php

session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0)
{
    $id = $_GET["uid"];
    $con = connectDb();
    $user = getUserData($id);
    $action = $_SERVER["PHP_SELF"] . "?uid=$id";
    closeDb($con);
    echo <<<EOT
   <div class="content">
   <h3 class="primary"><i class="fa fa-key"></i> Jelszó módosítása</h3>
   <form method="post" action="$action"> 
   <table id="responsiveTable" class="large-only" cellspacing="0">
    <tr align="left" class="primary">
    <th>Id</név>
    <th>Vezetéknév</th>
    <th>Keresztnév</th>
    <th>e-mail cím</th>
    <th>Felhasználónév</th>
    <th>Emelet</név>
    <th>Ajtó</név>
    <th>Törlés</th>    
    </tr>
    <tbody>
    <tr>
    <td>{$user['id']}</td>
    <td>{$user['firstname']}</td>
    <td>{$user['lastname']}</td>
    <td>{$user['email']}</td>
    <td>{$user['username']}</td>
    <td>{$user['floor']}</td>
    <td>{$user['door']}</td>
    <td><button type="input" name="submit" value="töröl" class="btn btn-danger btn-icon"><i class="fa fa-times"></i>Törlés</button></td>
    </tr>
    </tbody>
    </table>
    </form>
    <div class="buttons">
    <a href="allresidents.php"><button type="input" class="btn btn-success btn-icon"><i class="fa fa-times"></i>Mégsem</button></a>
    </div>
    </div>
EOT;
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {       $con = connectDb();
            killUser($id);
            closeDb($con);
            header("Location:allresidents.php?kill=1");
        
    }
}
else
{
    notLoggedIn();
}
htmlEnd();