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
        <link rel="stylesheet" href="css/style.css" type="text/css">
		<link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
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
			<div class="footer">
				<p class="textCenter">
					&copy; 2014 <a href="http://webariel.hu" target="_blank">PlainHome Version 0.1a</a></p>
			</div>
		</div>
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/reside.js"></script>

	</body>
	</html>
EOT;
}

function addDeposit() {
    echo <<<EOT
<div id="loginform">
<form action="adddepo.php" method="post">
	<fieldset>
		<legend>Új albetét felvétele</legend>
		<label for="floor">Emelet</label> 
		<input type="text" id="floor" name="floor"><br>
		<label for="door">Ajtó</label>
		<input type="text" id="door" name="door"><br>
		<label for="pass">Alapterület</label>
		<input type="text" id="area" name="area"><br>
		<label for="residents">Lakók száma</label>
		<input type="text" id="residents" name="residents"><br>
		<label for="note">Megjegyzés</label>
		<input type="text" id="note" name="note"><br>
		<input id="submit" type="submit" value="Felvesz">
</fieldset>
</form>
</div>
EOT;
}

function addUser() {
    echo <<<EOT
<div class="form-group">
<form action="add.php" method="post">
	<fieldset>
		<legend>Új felhasználó</legend>
		<label for="firstname">Vezetéknév</label>
		<input type="text" id="firstname" name="firstname"><br>
		<label for="lastname">Keresztnév</label>
		<input type="text" id="lastname" name="lastname"><br>
                <label for="email">E-mail</label>
		<input type="text" id="email" name="email"><br>
                <label for="username">Felhasználónév</label>
		<input type="text" id="username" name="username"><br>
                <label for="floor">Emelet</label>
		<input type="text" id="floor" name="floor"><br>
		<label for="door">Ajtó</label>
		<input type="text" id="door" name="door"><br>
                <label for="pass">Jelszó</label>
		<input type="password" id="pass" name="pass"><br>
		<label for="pass2">Jelszó újra</label>
		<input type="password" id="pass2" name="pass2"><br>
		<input id="submit2" type="submit" value="Hozzáad">
</fieldset>
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
		</div>
</div>

EOT;
}
function letterHead() {
    global $house;
    echo <<<EOT
<div id="menu">
  <ul>
      <li id="title"><a href="index.php">PlainHouse<span class="mini">{$house['name']}</span></a></span></li>
      <li><a href="login.php">Bejelentkezés</a></li>
      <li><a href="logout.php">Logout</a></li>                
      <li><a href="deposits.php">Deposits</a></li>                
      <li><a href="session.php">Session check</a></li>                
  </ul>    
</div>
   
EOT;
}

function copyRight() {
    echo <<<EOT
    <div id="footer">
    ©  
EOT;
    auto_copyright(2014);
    echo <<<EOT
    </div>
EOT;
}
function webheader() {
    global $house;
echo <<<EOT
    <div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="logo">
					<a href=""><img alt="PlainHome" src="pics/logo.png" /></a>
				</div>
			</div>
			<div class="col-md-4 userInfo">
                                <p class="textRight">{$house['name']}</p>
			</div>
		</div>
EOT;
}
