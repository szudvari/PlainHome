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

if (($_SESSION["admin"] > 0) || ($_SESSION["userid"] == $_GET["uid"])) {
    $id = $_GET["uid"];
    $con = connectDb();
    $user = getUserData($id);
    $action = $_SERVER["PHP_SELF"] . "?uid=$id";
    closeDb($con);
    echo <<<EOT
   <div class="content">
   <h3 class="primary"><i class="fa fa-key"></i> Jelszó módosítása</h3>
   <form method="post" action="$action" id="passwordchange"> 
   <table id="responsiveTable" class="large-only" cellspacing="0">
    <tr align="left" class="primary">
    <th>Id</név>
    <th>Vezetéknév</th>
    <th>Keresztnév</th>
    <th>e-mail cím</th>
    <th>telefonszám</th>
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
    <td>{$user['phone']}</td>
    <td>{$user['username']}</td>
    <td>{$user['floor']}</td>
    <td>{$user['door']}</td>
    <td><input type="password" name="pass1"></td>
    <td><input type="password" name="pass2"></td>
    <td>    <button type="input" name="submit" value="módosít" class="btn btn-success btn-icon"><i class="fa fa-refresh"></i>Módosít</button>   </td>
    </tr>
    </tbody>
    </table>

    </form>
EOT;

    echo '<div class="buttons">';

    if ($_SESSION["admin"] > 0) {
        echo '<a href="allresidents.php"><button type="input" class="btn btn-success btn-icon"><i class="fa fa-times"></i>Mégsem</button></a>';
    } else {
        echo '<a href="mydepo.php"><button type="input" class="btn btn-success btn-icon"><i class="fa fa-times"></i>Mégsem</button></a>';
    }
    echo <<<EOT
    </div>
    


EOT;

    @$pass1 = $_POST["pass1"];
    @$pass2 = $_POST["pass2"];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (($pass1=="") || ($pass2=="")) {
            echo '<p class="warning">Kérem, töltse ki a jelszó mezőket!</p>';
        } else {
            if ($pass1 != $pass2) {
                echo '<p class="warning">A két jelszó nem egyezik!</p>';
            } else {
                $user['pass']=$pass1;
                $password = encodePass($pass1);
                $con = connectDb();
                changeUserPassword($id, $password, $con);
                closeDb($con);
                sendMessageAfterUpdatePassword($user);
                if ($_SESSION["admin"] > 0) {
                    header("Location:allresidents.php?password=1");
                    exit();
                } else {
                    header("Location:mydepo.php?password=1");
                    exit();
                }
            }
        }
     echo "</div>";   
    }
} else {
    notLoggedIn();
}
htmlEnd();
ob_end_flush();
