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
		<link href="favicon.ico" rel="icon" type="image/x-icon" />
        <title>$title - $house</title>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/bootstrap-dialog.js" type="text/javascript"></script>
        <script src="js/plainhome.js" type="text/javascript"></script>
        <script src="js/form-validator.js" type="text/javascript"></script>
		<script src="js/print.js" type="text/javascript"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>     
        <link href="css/bootstrap-dialog.css" rel="stylesheet" type="text/css"/>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<link href="css/plainhome.css" rel="stylesheet">
		<link href="css/datepicker.css" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.js"></script>
	<![endif]-->
     </head>
    <body>

EOT;
}

function htmlEnd() {
    global $website;
    global $house;
    echo <<<EOT
			</div>
			<div class="footer">
				<p class="textCenter">
                                        Powered by <a href="http://webariel.hu?utm_source={$house['name']}&utm_medium=footer&utm_campaign=copyright" target="_blank">WebAriel <img src="./pics/wa_logo.png"></a> - &copy; 2014 <a href="http://webariel.hu?utm_source={$house['name']}&utm_medium=footer&utm_campaign=copyright2" target="_blank">PlainHouse Version {$website['version']}</a></p>
			</div>
		</div>
		<script src="js/stacktable.js" type="text/javascript"></script>
	</body>
	</html>
EOT;
}

function addDeposit() {
    echo <<<EOT

<div class="content_deposits">
    <button id="newdepo" value="ujAlbetetFelvetele" class="btn btn-success btn-icon"><i class="fa fa-plus"></i>Új albetét felvétele</button>
    <div id="newdeposit">
        <h3 class="primary"><i class="fa fa-plus"></i> Új albetét felvétele</h3>
    <form id="contactform" action="adddepo.php" method="post">
        <div class="formcolumn">

                <label for="floor">Emelet</label> 
                <input type="text" id="floor" name="floor" class="form-control">

            
                <label for="door">Ajtó</label>
                <input type="text" id="door" name="door" class="form-control">

            
                <label for="area">Lakás alapterülete</label>
                <input type="text" id="area" name="area" class="form-control">
            
            
                
            		</div>
			        <div class="formcolumn">
                <label for="residents">Lakók száma</label>
                <input type="text" id="residents" name="residents" class="form-control">
    
            
                <label for="area_ratio">Lakás tulajdoni hányad</label>
                <input type="text" id="area_ratio" name="area_ratio" class="form-control">

                                 
                <label for="note">Lakó neve</label>
                <input type="text" id="note" name="note" class="form-control">
</div>
<div class="buttons">
            <button type="input" name="submit" value="Hozzaad" class="btn btn-success btn-icon"><i class="fa fa-plus"></i> Hozzáad</button>
</div>
        </form>
    </div>
</div>
EOT;
}

function addUser() {
    $pass = randomPassword();
    echo <<<EOT
<div class="content">
    <button id="newuser" value="UjFelhasznaloFelvetele" class="btn btn-success btn-icon"><i class="fa fa-plus"></i>Új felhasználó felvétele</button>
   <div id="adduser"> 
   <h3 class="primary"><i class="fa fa-plus"></i> Új felhasználó felvétele</h3>

    <form id="contactform" action="add.php" method="post">
        <div class="formcolumn">
            <label for="firstname">Vezetéknév</label>
            <input type="text" id="firstname" name="firstname" class="form-control" data-validation="required">

            <label for="lastname">Keresztnév</label>
            <input type="text" id="lastname" name="lastname" class="form-control" data-validation="required">
        
            <label for="email">E-mail</label>
            <input type="text" id="email" name="email" class="form-control">
    
            <label for="phone">Telefonszám</label>
            <input type="text" id="phone" name="phone" class="form-control">
            </div>
            
            <div class="formcolumn">
            <label for="username">Felhasználónév</label>
            <input type="text" id="username" name="username" class="form-control" data-validation="required">
        	
            <label for="floor">Emelet</label>
            <input type="text" id="floor" name="floor" class="form-control" data-validation="required">

            <label for="door">Ajtó</label>
            <input type="text" id="door" name="door" class="form-control" data-validation="required">

            <label for="pass">Jelszó</label>
            <input type="password" id="pass" name="pass" value="$pass" class="form-control" data-validation="required">

            <label for="pass2">Jelszó újra</label>
            <input type="password" id="pass2" name="pass2" value="$pass" class="form-control">
		</div>
		<div class="buttons">
        <button type="input" name="submit" value="Hozzaad" class="btn btn-success btn-icon"><i class="fa fa-plus"></i>Hozzáad</button>
		</div>
    </form>
            Az új felhasználó automatikusan generált jelszava (többször nem jelenik meg):  <span class="pwarning">$pass</span><br>
            Ha szeretné megváltoztatni, írja át a jelszó mezőkben!
</div>
</div>
EOT;
}

function addAdmin() {
    $pass = randomPassword();
    echo <<<EOT
    <div class="content">
    <button id="newadmin" value="UjAdminFelvetele" class="btn btn-success btn-icon"><i class="fa fa-plus"></i>Új admin felvétele</button>
    <div id="addadmin">
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
            <input type="password" id="pass" name="pass" value="$pass" class="form-control"></div><br>

        <div class="form-group">
            <label for="pass2">Jelszó újra</label>
            <input type="password" id="pass2" name="pass2" value="$pass" class="form-control"></div><br>
            

        <button type="input" name="submit" value="Hozzaad" class="btn btn-success btn-icon"><i class="fa fa-sign-in"></i>Hozzáad</button>

    </form>
            Az új admin automatikusan generált jelszava (többször nem jelenik meg):<span class="pwarning"> $pass</span>
            Ha szeretné megváltoztatni, írja át a jelszó mezőkben!
</div>
    </div>
EOT;
}

function loginUser() {
    echo <<<EOT

		<div class="content">
			<h3 class="danger"><i class="fa fa-user"></i> A belépéshez adja meg felhasználónevét és jelszavát.</h3>
			<form action="auth.php" method="post" class="padTop">
			<div class="form-group">
					<label for="user">Felhasználónév</label>
					<input type="text" id="user" name="user" class="form-control">
			</div><br>
			<div class="form-group">		
					<label for="pass">Jelszó</label>
					<input type="password" id="pass" name="pass" class="form-control">
			</div><br>
			<button type="input" name="submit" value="Belepes" class="btn btn-success btn-icon"><i class="fa fa-sign-in"></i>Belépés</button>
			</form>

EOT;
}

function webheader($user) {
    global $house;
//	global $user;
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

    if (!isset($user["user"])) //nem belépet user
    {
        echo <<<EOT
	            <div>
	                <label for="toggle" class="toggle" data-open="Menü" data-close="Bezár" onclick></label>
	                <ul class="menu">
	                    <li><a href="login.php"><i class="fa fa-sign-in"></i> Belépés</a></li>
	                </ul>
	            </div>
EOT;
    }
    else if (($user["admin"] == 0) && (!(isset($user["admin_userid"])))) //belépett user
    {
        echo <<<EOT
	            <div>
	                <label for="toggle" class="toggle" data-open="Menü" data-close="Bezár" onclick></label>
	                <ul class="menu">
			    <li><a href="index.php">Home</a></li>
	                    <li><a href="mydepo.php">Saját adataim</a></li>
			    <li><a data-toggle="modal" href="#signOut">Kilépés <i class="fa fa-sign-out"></i></a></li>                
	                </ul>
	            </div>
EOT;
    }
    else if (($user["admin"] > 0) && (!(isset($user["depositid"]))))  //belépett admin
    {
        echo <<<EOT
	            <div>
	                <label for="toggle" class="toggle" data-open="Menü" data-close="Bezár" onclick></label>
	                <ul class="menu">
			    <li><a href="deposits.php">Albetétek</a></li>                
	                    <li><a href="stat.php">Statisztikák</a></li>
	                    <li><a href="basedata.php">Alapdíjak</a></li>
	                    <li><a href="board_admin.php">Üzenőfal</a></li>
	                    <li><a href="documents.php">Dokumentumok</a></li>
	                    <li><a href="allresidents.php">Felhasználók</a></li>
	                    <li><a href="listadmin.php">Adminisztrátorok</a></li>
                            <li><a data-toggle="modal" href="#signOut">Kilépés <i class="fa fa-sign-out"></i></a></li>     
	                </ul>
	            </div>
EOT;
    }
    else if (($user["admin"] > 0 ) && ((isset($user["depositid"]))))  //user akinek van admin joga
    {
        echo <<<EOT
	            <div>
	                <label for="toggle" class="toggle" data-open="Menü" data-close="Bezár" onclick></label>
	                <ul class="menu">
	                    <li><a href="index.php">Home</a></li>            
                            <li><a href="mydepo.php">Saját adataim</a></li>	                   
                            <li><a href="deposits.php">Albetétek</a></li>                
                            <li><a href="stat.php">Statisztikák</a></li>                
	                    <li><a href="basedata.php">Alapdíjak</a></li>
                            <li><a href="board_admin.php">Üzenőfal</a></li>
                            <li><a href="documents.php">Dokumentumok</a></li>
                            <li><a href="allresidents.php">Felhasználók</a></li>
                            <li><a data-toggle="modal" href="#signOut">Kilépés <i class="fa fa-sign-out"></i></a></li>
	                </ul>
	            </div>
EOT;
    }
    echo <<<EOT
                </div>
			</div>
	    </div>
		<!-- -- KILEPES MODAL -- -->
		<div class="modal fade" id="signOut" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<p class="lead">Biztosan ki akar lépni?</p>
					</div>
					<div class="modal-footer">
						<a href="index.php?logout=1" class="btn btn-success btn-icon-alt">Kilépés <i class="fa fa-sign-out"></i></a>
						<button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
					</div>
				</div>
			</div>
		</div>
		
EOT;
}

function notLoggedIn() {
    echo <<<EOT

		<div class="content">
			<h3>Ön nem jelentkezett be, vagy nincs jogosultsága az oldal megtekintéséhez!</h3>
                </div>
    
EOT;
}

function notLoggedIn2() {
    echo <<<EOT

		<div class="content">
			<h3>Önnek a kért művelethez nincs jogosultsága!</h3>
                </div>
    
EOT;
   $url=$_SERVER['HTTP_REFERER'];
   header("Refresh: 1; url=$url");
}

function updateDeposit($depo) {
        echo <<<EOT

                    <div class="content">
                        <h3>Albetét módosítása</h3>
                        <form id="contactform" action="updatedepo.php" method="post">
                            <div class="formcolumn">
                                <label for="floor">Emelet</label> 
                                <input type="text" id="floor" name="floor" class="form-control" value="{$depo['floor']}">
		            <label for="door">Ajtó</label>
		            <input type="text" id="door" name="door" class="form-control" value="{$depo['door']}">
		            <label for="area">Lakás alapterülete</label>
		            <input type="text" id="area" name="area" class="form-control" value="{$depo['area']}">
		        </div>
		        <div class="formcolumn">
		            <label for="residents">Lakók száma</label>
		            <input type="text" id="residents" name="residents" class="form-control" value="{$depo['residents_no']}">
		            <label for="area_ratio">Lakás tulajdoni hányad</label>
		            <input type="text" id="area_ratio" name="area_ratio" class="form-control" value="{$depo['area_ratio']}">
		            <label for="note">Lakó neve</label>
		            <input type="text" id="note" name="note" class="form-control" value="{$depo['resident_name']}">
		            <input type="hidden" id="id" name="id" class="form-control" value="{$depo['id']}">
		        </div>
		        <div class="buttons">
		            <button type="input" name="submit" value="Modosit" class="btn btn-success btn-icon"><i class="fa fa-save"></i> Módosít</button>
		        </div>
		    </form>
		</div>
		</div>
                
EOT;
}

function updateBaseData($data) {
    echo <<<EOT

	<div class="content">
                <button id="updatedata" value="alapdijakModositasa" class="btn btn-success btn-icon"><i class="fa fa-refresh"></i>Alapdíjak módosítása</button>
                <div id="changedata">
		    <h3>Alapdíjak módosítása</h3>
		    <form id="contactform" action="update-base.php" method="post">
		        <div class="formcolumn">
		            <label for="ccost">{$data[0]["name"]}</label> 
		            <input type="text" id="ccost" name="ccost" class="form-control" value="{$data[0]["yearly_amount"]}"> Ft{$data[0]["multiplier"]}
		        </div>
		        <div class="formcolumn">
		            <label for="grabage">{$data[1]["name"]}</label>
		            <input type="text" id="grabage" name="grabage" class="form-control" value="{$data[1]["yearly_amount"]}"> Ft{$data[1]["multiplier"]} 
		        </div>
		        <div class="buttons">
		            <button type="input" name="submit" value="Modosit" class="btn btn-success btn-icon"><i class="fa fa-save"></i> Módosít</button>
                        </div>
		    </form>
                        <div class="buttons">
                            <a href="basedata.php?noupdate=1"><button class="btn btn-success btn-icon"><i class="fa fa-times"></i> Mégsem</button></a>
                        </div>                
                    </div>
	</div>
                
EOT;
}

function sendMessage() {
    echo <<<EOT

		<a data-toggle="modal" href="#newMsg" class="btn btn-success btn-icon floatLeft"><i class="fa fa-envelope"></i> Üzenet küldése</a>
		<!-- -- Uj uzenet Modal -- -->
		<div class="modal fade" id="newMsg" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header modal-primary">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h4 class="modal-title">Kérdése van? Információra van szüksége?</h4>
					</div>
					<form action="message.php" method="post">
						<div class="modal-body">
							<div class="form-group">
		                        <label for="subject">Tárgy</label>
								<input type="text" class="form-control" name="subject" id="subject" value="" />
								<span class="help-block">Kérjük, adja meg üzenete tárgyát.</span>
		                    </div>
							<div class="form-group">
								<label for="comment">Üzenet</label>
								<textarea class="form-control" name="comment" id="comment" rows="4"></textarea>
								<span class="help-block">Kérjük fogalmazza meg röviden, lényegretörően üzenetét. HTML kód nem használható!</span>
							</div>
						</div>
						<div class="modal-footer">
							<button type="input" name="submit" value="sendMsg" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Küldés</button>
							<button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
						</div>
					</form>
				</div>
			</div>
		</div>		

EOT;
}

function sendMessageNoUser() {
    echo <<<EOT
		<div class="content">
				<h3 class="info">Kérdése van? Információra van szüksége?</h3>
		<p class="lead">Küldjön üzenetet!</p>
		<form action="message.php" method="post" class="padTop">
                        <div class="form-group">
				<label for="from">Az Ön e-mail címe</label>
				<input type="text" class="form-control" name="from" id="from" value="" />
			</div>			
                        <div class="form-group">
				<label for="subject">Tárgy</label>
				<input type="text" class="form-control" name="subject" id="subject" value="" />
			</div>
			<div class="form-group">
				<label for="comment">Üzenet</label>
				<textarea class="form-control" name="comment" id="comment" rows="4"></textarea>
			</div>
			<button type="input" name="submit" value="uzenetKuldese" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Küldés</button>
		</form>
		</div>
EOT;
}

function welcomeIndexNoUser() {
    global $house;
    echo <<<EOT
		<div class="content">
				<h3 class="info">Köszöntjük oldalunkon</h3> 
               <h4>Ön a "Társasház - {$house['name']}" - weboldalára látogatott.<br></h4>
                   <p class="lead"> 
                    Ha Ön a ház lakója, kérem, jelentkezzen be!<br>
                    Amennyiben még nincs felhasználóneve vagy nem tud belépni, küldjön üzenetet!
                </p>
                </div>
EOT;
}

function welcomeIndexUser($user, $ccost, $balance) {
    global $house;
    $msg = getLatestBoardMessage();
    $date = date("Y.m.d");
    $cost = number_format($ccost, 0, ',', ' ');
    if ($balance < 0) {
        $abalance = $balance * -1;
    }
    else {
        $abalance = $balance;
    }
    $abalance = number_format($abalance, 0, ',', ' ');
    echo <<<EOT
    
<div class="content">
		<div class="row">
	<div class="col-md-6">
		<img alt="Tenant Avatar" src="pics/user_avatar.png" class="avatar" />
		<p class="lead welcomeMsg">Üdvözöljük egyéni oldalán {$user['firstname']} {$user['lastname']}!</p>
		<p class="lead welcomeMsg">A "Saját adataim" menülinkre kattintva, megtekintheti részletes adatait és megváltoztathatja belépési jelszavát!</p>
EOT;
if ($msg)
{
	echo <<<EOT
		<div class="panel panel-primary">
		<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-bullhorn"></i> Legfrisebb hír</h3>
		</div>
                <div class="panel-body"><p>{$msg['title']}<span style="float: right">{$msg['creation_date']}</span></p>
		<p>{$msg['text']}</p>
		<p><a href="board.php"><button type="button" class="btn btn-info"><i class=""></i>További hírek</button></a></p>
		</div>
		</div>
EOT;
}

echo <<<EOT
	</div>
	<div class="col-md-6">
EOT;
    if ($balance < 0)
    {
        echo <<<EOT
                <div class="alertMsg danger"><i class="fa fa-warning"></i> Az Ön közösköltségének aktuális egyenlege: <span class="floatLeft">$abalance Ft elmaradás</span></div>
EOT;
    }
    else
    {
        echo <<<EOT
                <div class="alertMsg success"><i class="fa fa-info-circle"></i> Az Ön közösköltségének aktuális egyenlege: <span class="floatLeft">$abalance Ft túlfizetés</span></div> 
EOT;
    }
    echo <<<EOT
                <div class="alertMsg success"><i class="fa fa-info-circle"></i> Az Ön havi közösköltsége: <span class="floatLeft">$cost Ft</span></div>
                <div class="panel panel-info">
                <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-warning"></i> Havi fizetési kötelezettség<span class="floatRight">$date </span></h3>
                </div>
                <div class="panel-body">Minden hónap {$house['payment_day']}. napjáig legyen szives befizetését rendezni!</div>
                </div>
                <div class="panel panel-warning">
                <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-info-circle"></i> Üzemeltetési bankszámlaszám:<span class="floatRight"></span></h3>
                </div>
                <div class="panel-body"> <span style="font-weight:bold;">{$house['bank_account']}</span><br>Kérjük a befizetéseket a fenti bankszámlára teljesíteni!</div>
                </div>
                </div>		
                </div>
EOT;
}

function changePassword($id) {
    echo <<<EOT
<a href="update_upassword.php?uid=$id"><button class="btn btn-success btn-icon floatRight"><i class="fa fa-refresh"></i> Jelszó módosítása</button></a>
EOT;
}

function newPayment ($id) {
          echo <<<EOT

                    <div class="content">
                        <h3>Befizetés rögzítése</h3>
                        <form id="paymentform" action="insertpayment.php" method="post">
                            <div class="formcolumn">
                                <label for="payment">Összeg</label> 
                                <input type="text" id="payment" name="payment" class="form-control" data-validation="required">
                                
    </div>
                  <div class="formcolumn">
                                <label for="account_date">Könyvelés dátuma</label> 
                                <input type="date" id="account_date" name="account_date" class="form-control" data-validation="required">
                                <span class="help-block">Kérem, "éééé-hh-nn" vagy "éééé.hh.nn" formátumot használjon!</span>
                                <input type="hidden" id="did" name="did" value="$id">
    </div>
		        <div class="buttons">
		            <button type="input" name="submit" value="Felvesz" class="btn btn-success btn-icon"><i class="fa fa-dollar"></i> Befizetés rögzítése</button>
		        </div>
EOT;
}

function uploadFile () {
 echo <<<EOT

<a data-toggle="modal" href="#uploadFile" class="btn btn-success btn-icon floatLeft"><i class="fa fa-upload"></i> File feltöltése</a>
<!-- -- Filefeltoltes Modal -- -->
<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Új file feltöltése</h4>
            </div>
            <form action="upload_file.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">File név</label>
                        <input type="file" class="form-control" name="file" id="file" value="" data-validation="required">
                        <span class="help-block">Válassza ki a feltölteni kívánt file-t! "gif", "jpeg", "jpg", "png", "pdf" fileok feltöltésére van lehetőség.</span>
                    </div>
                    <div class="form-group">
                        <label for="shortname">Dokumentum neve</label>
                        <input type="text" class="form-control" name="shortname" id="shortname" value="" data-validation="required">
                        <span class="help-block">A dokumentum neve (amit a felhasználók látnak).</span>
                    </div>
                    <div class="form-group">
                        <label for="description">Leírás</label>
                        <input type="text" class="form-control" name="description" id="description" value="" />
                        <span class="help-block">Leírás a dokumentumról (nem kötelező).</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="input" name="submit" value="uploadFile" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Feltöltés</button>
                    <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                </div>
            </form>
        </div>
    </div>
</div>		

EOT;
}

function documents () {
    $files = filesInDirectory("documents");
     echo <<<EOT
     <div class="content">
                     <h3 class="primary"><i class="fa fa-file-pdf-o"></i> Feltöltött dokumentumok</h3>
    <table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> File név </th>
   <th> Megnéz </th>
   <th> Törlés </th>
</tr>
<tbody">  
EOT;
    foreach ($files as $row) {
        echo '<tr>';
        echo "<td>$row</td>";
        echo "<td><a href=\"./documents/$row\" target='_blank'>Megnéz</td>";
        echo "<td><a href=\"delfile.php?file=$row\">Törlés</a></td>";
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    
}

function newBoardMessage () {
 echo <<<EOT

<a data-toggle="modal" href="#newBMsg" class="btn btn-success btn-icon floatLeft"><i class="fa fa-upload"></i> Új hír feltöltése</a>
<!-- -- Filefeltoltes Modal -- -->
<div class="modal fade" id="newBMsg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Új hír</h4>
            </div>
            <form action="new_boardmessage.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Cím</label>
                        <input type="text" class="form-control" name="title" id="title" value="" data-validation="required">
                        <span class="help-block">A hír címe</span>
                    </div>
                    <div class="form-group">
                        <label for="text">Üzenet</label>
                        <textarea rows="6" cols="50" class="form-control" name="text" id="text" data-validation="required"></textarea>
                        <span class="help-block">Az hír törzse</span>
                    </div>
                    <div class="form-group">
                        <label for="valid_till">Érvényesség</label>
                        <input type="date" class="form-control" name="valid_till" id="valid_till" value="" />
                        <span class="help-block">Az üzenet eddig érvényes. Hagyja üresen, ha nem korátozott az érvényességi idő. </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="input" name="submit" value="uploadFile" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Feltöltés</button>
                    <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                </div>
            </form>
        </div>
    </div>
</div>		

EOT;
}
function adminStat () {
    echo <<<EOT
		<div class="content">
			<h3 class="info"><i class="fa fa-bar-chart-o"></i> Statisztikák</h3>
	        <div class="list-group">
				<a data-toggle="modal" href="#yearly" class="list-group-item">Éves kimutatás</a>
				<a data-toggle="modal" href="#yearlybydepo" class="list-group-item">Éves kimutatás lakásonként</a>
				<a data-toggle="modal" href="#payment" class="list-group-item">Befizetések listázása</a>
				<a data-toggle="modal" href="#depopayment" class="list-group-item">Befizetések listázása lakásonként</a>
				<a data-toggle="modal" href="#housepayment" class="list-group-item">Nem könyvelhető befizetések listázása</a>
				<a data-toggle="modal" href="#depoccosts" class="list-group-item">Közösköltség alakulása lakásonként</a>
	        </div>
		
	</div>
     <!-- -- Eves kimutatas modal-- -->
<div class="modal fade" id="yearly" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Éves statisztika</h4>
            </div>
            <form action="yearlystat.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year">Melyik évre kíváncsi?</label>
                        <input type="text" class="form-control" name="year" id="year" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az évszámot!</span>
                    </div>
                    <div class="modal-footer">
                        <button type="input" name="submit" value="editPassword" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Választ</button>
                        <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
    <!-- -- Eves kimutatas lakasonkent modal-- -->
<div class="modal fade" id="yearlybydepo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Éves statisztika lakásonként</h4>
            </div>
            <form action="yearlydepostat.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year">Melyik évre kíváncsi?</label>
                        <input type="text" class="form-control" name="year" id="year" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az évszámot!</span>
                    </div>
                    <div class="form-group">
                        <label for="year">Melyik lakásra kíváncsi</label>
                        <input type="text" class="form-control" name="floor" id="floor" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az emeletet!</span>
                     </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="door" id="door" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az ajtót!</span>
                    </div>
                    <div class="modal-footer">
                        <button type="input" name="submit" value="editPassword" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Választ</button>
                        <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
 <!-- -- Befizetések listázása-- -->
<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Éves statisztika</h4>
            </div>
            <form action="paymentstat.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year">Melyik évre kíváncsi?</label>
                        <input type="text" class="form-control" name="year" id="year" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az évszámot!</span>
                    </div>
                    <div class="modal-footer">
                        <button type="input" name="submit" value="editPassword" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Választ</button>
                        <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- -- Nem könyvelhető befizetések listázása-- -->
<div class="modal fade" id="housepayment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Éves statisztika</h4>
            </div>
            <form action="housepaymentstat.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year">Melyik évre kíváncsi?</label>
                        <input type="text" class="form-control" name="year" id="year" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az évszámot!</span>
                    </div>
                    <div class="modal-footer">
                        <button type="input" name="submit" value="editPassword" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Választ</button>
                        <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>   
 <!-- -- Befizetesek lakasonkent modal-- -->
<div class="modal fade" id="depopayment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Befizetések listázása lakásonként</h4>
            </div>
            <form action="depopaymentstat.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year">Melyik évre kíváncsi?</label>
                        <input type="text" class="form-control" name="year" id="year" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az évszámot!</span>
                    </div>
                    <div class="form-group">
                        <label for="year">Melyik lakásra kíváncsi</label>
                        <input type="text" class="form-control" name="floor" id="floor" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az emeletet!</span>
                     </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="door" id="door" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az ajtót!</span>
                    </div>
                    <div class="modal-footer">
                        <button type="input" name="submit" value="editPassword" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Választ</button>
                        <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- -- Kozoskoltseg lakasonkent modal-- -->
<div class="modal fade" id="depoccosts" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Közösköltség alakulása lakásonként</h4>
            </div>
            <form action="depoccost.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year">Melyik évre kíváncsi?</label>
                        <input type="text" class="form-control" name="year" id="year" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az évszámot!</span>
                    </div>
                    <div class="form-group">
                        <label for="year">Melyik lakásra kíváncsi</label>
                        <input type="text" class="form-control" name="floor" id="floor" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az emeletet!</span>
                     </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="door" id="door" value="" data-validation="required"/>
                        <span class="help-block">Adja meg az ajtót!</span>
                    </div>
                    <div class="modal-footer">
                        <button type="input" name="submit" value="editPassword" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Választ</button>
                        <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>
EOT;
}

function reAccountPayment ($id, $payment) {
          echo <<<EOT

                    <div class="content">
                        <h3>Befizetés átkönyvelése</h3>
                        <form id="paymentform" action="reaccountpayment.php" method="post">
                            <div class="formcolumn">
                                <label for="payment">Emelet</label> 
                                <input type="text" id="floor" name="floor" class="form-control" data-validation="required">
                         </div>       
                         <div class="formcolumn">
                                <label for="account_date">Ajtó</label> 
                                <input type="text" id="door" name="door" class="form-control" data-validation="required">
                                <input type="hidden" id="id" name="id" value="$id">
                                <input type="hidden" id="oldid" name="oldid" value=" {$payment['oldid']}">
                                <input type="hidden" id="amount" name="amount" value="{$payment['amount']}">
                                
    </div>
		        <div class="buttons">
		            <button type="input" name="submit" value="Felvesz" class="btn btn-success btn-icon"><i class="fa fa-dollar"></i> Befizetés átkönyvelése</button>
		        </div>
EOT;
}

function updateCcost($ccost) {
        echo <<<EOT

                    <div class="content">
                        <h3>Közösköltség módosítása</h3>
                        <form id="contactform" action="update_ccost.php" method="post">
                            <div class="formcolumn">
                            <label for="ccost">Közösköltség összege</label>
		            <input type="text" id="ccost" name="ccost" class="form-control" value="{$ccost['ccost']}">
		            <input type="hidden" id="id" name="id" class="form-control" value="{$ccost['id']}">
		        </div>
		        <div class="buttons">
		            <button type="input" name="submit" value="Modosit" class="btn btn-success btn-icon"><i class="fa fa-save"></i> Módosít</button>
		        </div>
		    </form>
		</div>
		</div>
                
EOT;
}
function uploadCSV () {
 echo <<<EOT
<div class="content">
<a data-toggle="modal" href="#uploadFile" class="btn btn-success btn-icon floatLeft"><i class="fa fa-upload"></i> Havi befizetések csoportos feltöltése</a>
<!-- -- Filefeltoltes Modal -- -->
<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                <h4 class="modal-title">Új file feltöltése</h4>
            </div>
            <form action="importaccountdata.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">File név</label>
                        <input type="file" class="form-control" name="file" id="file" value="" data-validation="required">
                        <span class="help-block">Válassza ki a feltölteni kívánt file-t! "csv" fileok feltöltésére van lehetőség.</span>
                    </div>
                    
                <div class="modal-footer">
                    <button type="input" name="submit" value="uploadFile" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Feltöltés</button>
                    <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
                </div>
            </form>
        </div>
    </div>
</div>		
</div>

EOT;
}
function newOcost ($id) {
          echo <<<EOT

                    <div class="content">
                        <h3>Előírás rögzítése</h3>
                        <form id="paymentform" action="insertocost.php" method="post">
                            <div class="formcolumn">
                                <label for="cost">Összeg</label> 
                                <input type="text" id="cost" name="cost" class="form-control" data-validation="required">
                                
    </div>
    <div class="formcolumn">
                                <label for="title">Jogcím</label> 
                                <input type="text" id="title" name="title" class="form-control" data-validation="required">
                                
    </div>
    
                  <div class="formcolumn">
                                <label for="date">Rögzítés dátuma</label> 
                                <input type="date" id="date" name="date" class="form-control" data-validation="required">
                                <span class="help-block">Kérem, "éééé-hh-nn" vagy "éééé.hh.nn" formátumot használjon!</span>
                                <input type="hidden" id="did" name="did" value="$id">
    </div>
		        <div class="buttons">
	            <button type="input" name="submit" value="Felvesz" class="btn btn-success btn-icon"><i class="fa fa-dollar"></i> Előírás rögzítése</button>
		        </div>
EOT;
}
function updateUser($user) {
    echo <<<EOT
   <div id="adduser"> 
       <form id="contactform" action="update_user.php" method="post">
        <div class="formcolumn">
            <label for="firstname">Vezetéknév</label>
            <input type="text" id="firstname" name="firstname" class="form-control" data-validation="required" value="{$user['firstname']}">

            <label for="lastname">Keresztnév</label>
            <input type="text" id="lastname" name="lastname" class="form-control" data-validation="required" value="{$user['lastname']}">
            </div>
            
            <div class="formcolumn">            
            <label for="email">E-mail</label>
            <input type="text" id="email" name="email" class="form-control" value="{$user['email']}">
    
            <label for="phone">Telefonszám</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{$user['phone']}">
            
            <label for="username">Felhasználónév</label>
            <input type="text" id="username" name="username" class="form-control" data-validation="required" value="{$user['username']}">
            <input type="hidden" id="user_id" name="user_id" value="{$user['id']}">
        	
            
            </div>
            <div class="buttons">
                <button type="input" name="submit" value="Hozzaad" class="btn btn-success btn-icon"><i class="fa fa-refresh"></i>Módosít</button>
            </div>
    </form>            
            <div class="buttons">
                <a href="allresidents.php"><button type="input" class="btn btn-success btn-icon"><i class="fa fa-times"></i>Mégsem</button></a>
            </div>

            
</div>
</div>
EOT;
}