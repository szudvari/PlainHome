<?php

include_once 'config.php';
include_once 'functions.php';

function htmlHead($title, $house) {
    echo <<<EOT
	<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>$title - $house</title>
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/bootstrap-dialog.js" type="text/javascript"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>     
        
        <link href="css/bootstrap-dialog.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
        <link rel="stylesheet" href="css/style.css" type="text/css">
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.js"></script>
	<![endif]-->
     </head>
    <body>

EOT;
}

function htmlEnd() {
    echo <<<EOT
			</div>
			<div class="footer">
				<p class="textCenter">
					&copy; 2014 <a href="http://webariel.hu" target="_blank">PlainHome Version 0.1a</a></p>
			</div>
		</div>
		<script src="js/jquery.js"></script>
		<script src="js/reside.js"></script>
		<script src="js/plainhome.js"></script>
	</body>
	</html>
EOT;
}

function addDeposit() {
    echo <<<EOT

<div class="content">
    <button id="newdepo" value="Új albetét felvétele" class="btn btn-success btn-icon"><i class="fa fa-plus"></i>Új albetét felvétele</button>
    <div id="newdeposit">
        <h3>Új albetét felvétele</h3>
    <form id="contactform" action="adddepo.php" method="post">
        <div class="formcolumn">

                <label for="floor">Emelet</label> 
                <input type="text" id="floor" name="floor" class="form-control">

            
                <label for="door">Ajtó</label>
                <input type="text" id="door" name="door" class="form-control">

            
                <label for="area">Lakás alapterülete</label>
                <input type="text" id="area" name="area" class="form-control">
            
            
                <label for="garage_area">Garázs alapterülete</label>
                <input type="text" id="garage_area" name="garage_area" class="form-control">

					<label for="watermeter">Vízóra van</label>
	                <input type="checkbox" id="watermeter" name="watermeter" class="form-control">

            		</div>
			        <div class="formcolumn">
                <label for="residents">Lakók száma</label>
                <input type="text" id="residents" name="residents" class="form-control">
    
            
                <label for="area_ratio">Lakás tulajdoni hányad</label>
                <input type="text" id="area_ratio" name="area_ratio" class="form-control">

           
                <label for="garage_area_ratio">Garázs tulajdoni hányad</label>
                <input type="text" id="garage_area_ratio" name="garage_area_ratio" class="form-control">
            
                <label for="note">Lakó neve</label>
                <input type="text" id="note" name="note" class="form-control">
</div>
<div class="buttons">
            <button type="input" name="submit" value="Hozzáad" class="btn btn-success btn-icon"><i class="fa fa-plus"></i> Hozzáad</button>
</div>
        </form>
    </div>
</div>
EOT;
}

function addUser() {
    echo <<<EOT
<div class="content">
    <h3>Új felhasználó felvétele</h3>

    <form action="add.php" method="post">
        <div class="form-group">

            <label for="firstname">Vezetéknév</label>
            <input type="text" id="firstname" name="firstname" class="form-control"></div><br>

        <div class="form-group">
            <label for="lastname">Keresztnév</label>
            <input type="text" id="lastname" name="lastname" class="form-control"></div><br>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="text" id="email" name="email" class="form-control"></div><br>

        <div class="form-group">
            <label for="username">Felhasználónév</label>
            <input type="text" id="username" name="username" class="form-control"></div><br>

        <div class="form-group">
            <label for="floor">Emelet</label>
            <input type="text" id="floor" name="floor" class="form-control"></div><br>

        <div class="form-group">
            <label for="door">Ajtó</label>
            <input type="text" id="door" name="door" class="form-control"></div><br>

        <div class="form-group">
            <label for="pass">Jelszó</label>
            <input type="password" id="pass" name="pass" class="form-control"></div><br>

        <div class="form-group">
            <label for="pass2">Jelszó újra</label>
            <input type="password" id="pass2" name="pass2" class="form-control"></div><br>


        <button type="input" name="submit" value="Hozzáad" class="btn btn-success btn-icon"><i class="fa fa-plus"></i>Hozzáad</button>
    </form>
</div>
EOT;
}

function addAdmin() {
    echo <<<EOT
    <div class="content">
    <h3>Új admin felvétele</h3>
    <form action="adda.php" method="post">
        <div class="form-group">
            <label for="username">Felhasználónév</label>
            <input type="text" id="username" name="username" class="form-control"></div><br>

        <div class="form-group">
            <label for="email">E-mail cím</label>
            <input type="text" id="email" name="email" class="form-control"></div><br>

        <div class="form-group">
            <label for="pass">Jelszó</label>
            <input type="password" id="pass" name="pass" class="form-control"></div><br>

        <div class="form-group">
            <label for="pass2">Jelszó újra</label>
            <input type="password" id="pass2" name="pass2" class="form-control"></div><br>


        <button type="input" name="submit" value="Hozzáad" class="btn btn-success btn-icon"><i class="fa fa-sign-in"></i>Hozzáad</button>

    </form>
</div>
EOT;
}

function loginUser() {
    echo <<<EOT

		<div class="content">
			<h3>A belépéshez adja meg felhasználónevét és jelszavát.</h3>
			<form action="auth.php" method="post" class="padTop">
			<div class="form-group">
					<label for="user">Felhasználónév</label>
					<input type="text" id="user" name="user" class="form-control">
			</div><br>
			<div class="form-group">		
					<label for="pass">Jelszó</label>
					<input type="password" id="pass" name="pass" class="form-control">
			</div><br>
			<button type="input" name="submit" value="Belépés" class="btn btn-success btn-icon"><i class="fa fa-sign-in"></i>Belépés</button>
			</form>

EOT;
}

function webheader($user) {
    global $house;
    echo <<<EOT
    <div class="container">
	    <div class="row">
	        <div class="col-md-8">
	            <div class="logo">
	                <a href="index.php"><img alt="PlainHome" src="pics/logo.png" /></a>
	            </div>
	        </div>
	        <div class="col-md-4 userInfo">
	            <p class="textRight"><i class="fa fa-home"></i> {$house['name']}</p>
	        </div>
	        <div>
	            <div class="nav">
	                <input type="checkbox" id="toggle" />
	            
EOT;

    if (!isset($user)) //nem belépet user
    {
        echo <<<EOT
	            <div>
	                <label for="toggle" class="toggle" data-open="Menü" data-close="Bezár" onclick></label>
	                <ul class="menu">
	                    <li><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></li>
	                    <li><a href="adminlogin.php">Admin login</a></li>
	                </ul>
	            </div>
EOT;
    }
    else if ($user == 0) //belépett user
    {
        echo <<<EOT
	            <div>
	                <label for="toggle" class="toggle" data-open="Menü" data-close="Bezár" onclick></label>
	                <ul class="menu">
	                    <li><a href="adminlogin.php">Admin login</a></li>
	                    <li><a href="index.php?logout=1">Logout <i class="fa fa-sign-out"></i></a></li>                
 	                    <li><a href="mydepo.php">Saját adataim</a></li>
                            <li><a href="session.php">Session check</a></li>
	                </ul>
	            </div>
EOT;
    }
    else if ($user == 1) //belépett admin
    {
        echo <<<EOT
	            <div>
	                <label for="toggle" class="toggle" data-open="Menü" data-close="Bezár" onclick></label>
	                <ul class="menu">
	                    <li><a href="index.php?logout=1">Logout <i class="fa fa-sign-out"></i></a></li>                
	                    <li><a href="deposits.php">Deposits</a></li>                
	                    <li><a href="session.php">Session check</a></li>
	                </ul>
	            </div>
EOT;
    }

        echo <<<EOT
                </div>
			</div>
	    </div>		
EOT;
    }


function notLoggedIn () {
    echo <<<EOT

		<div class="content">
			<h3>Ön nem jelentkezett be, vagy nincs jogosultsága az oldal megtekintéséhez!</h3>
                </div>
    
EOT;
}

function updateDeposit($depo) {
    $wm = "";
    if ($depo['watermeter'] != 0)
    {
        $wm = "checked";
    }
    echo <<<EOT

		<div class="content">
		    <h3>Albetét módosítása</h3>
		    <form id="contactform">
		        <div class="formcolumn">
		            <label for="floor">Emelet</label> 
		            <input type="text" id="floor" name="floor" class="form-control" value="{$depo['floor']}">
		            <label for="door">Ajtó</label>
		            <input type="text" id="door" name="door" class="form-control" value="{$depo['door']}">
		            <label for="area">Lakás alapterülete</label>
		            <input type="text" id="area" name="area" class="form-control" value="{$depo['area']}">
		            <label for="garage_area">Garázs alapterülete</label>
		            <input type="text" id="garage_area" name="garage_area" class="form-control" value="{$depo['garage_area']}">
		            <label for="watermeter">Vízóra van</label>
		            <input type="checkbox" id="watermeter" name="watermeter" $wm class="form-control">   
		        </div>
		        <div class="formcolumn">
		            <label for="residents">Lakók száma</label>
		            <input type="text" id="residents" name="residents" class="form-control" value="{$depo['residents_no']}">
		            <label for="area_ratio">Lakás tulajdoni hányad</label>
		            <input type="text" id="area_ratio" name="area_ratio" class="form-control" value="{$depo['area_ratio']}">
		            <label for="garage_area_ratio">Garázs tulajdoni hányad</label>
		            <input type="text" id="garage_area_ratio" name="garage_area_ratio" class="form-control" value="{$depo['garage_area_ratio']}">
		            <label for="note">Lakó neve</label>
		            <input type="text" id="note" name="note" class="form-control" value="{$depo['resident_name']}">
		            <input type="hidden" id="id" name="id" class="form-control" value="{$depo['id']}">
		        </div>
		        <div class="buttons">
		            <button type="input" name="submit" value="Módosít" class="btn btn-success btn-icon"><i class="fa fa-refresh"></i> Módosít</button>
		        </div>
		    </form>
		</div>
		</div>
EOT;
}