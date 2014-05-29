<?php
session_start();
include_once 'config.php';
include_once 'db.php';
include_once 'html.php';

$id = $_GET["uid"];
$con = connectDb();
$user = getUserData($id);
$action = $_SERVER["PHP_SELF"] . "?uid=$id";
closeDb($con);
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
echo <<<EOT
   <div class="content">
   <h3 class="primary"> Jelszó módosítása </h3>
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
    <th>Új jelszó</th>
    <th>Új jelszó újra</th>
    <th>Módosít</th>    
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
    <td><input type="password" name="pass1"></td>
    <td><input type="password" name="pass2"></td>
    <td><button type="input" name="submit" value="módosít" class="btn btn-success btn-icon"><i class="fa fa-refresh"></i>Módosít</button></td>
    </tr>
    </tbody>
    </table>
    </form>
    <div class="buttons">
    <a href="allresidents.php"><button type="input" class="btn btn-success btn-icon"><i class="fa fa-times"></i>Mégsem</button></a>
    </div>
    </div>
EOT;
@$pass1 = $_POST["pass1"];
@$pass2 = $_POST["pass2"];
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if ($pass1 != $pass2)
    {
        echo '<p id="notloggedin">A két jelszó nem egyezik!</p>';
    }
    else
    {
        $password = encodePass($pass1);
        $con = connectDb();
        changeUserPassword($id, $password, $con);
        closeDb($con);
        header("Location:allresidents.php?password=1");
    }
}
htmlEnd();
