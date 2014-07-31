<?php

include_once 'functions.php';
include_once 'js.php';

function connectDb() {
    global $db;
    $con = mysql_connect($db['host'], $db['user'], $db['pass']);

    if (!$con) {
        die('Nem tudok kapcsolódni: ' . mysql_error());
    }
    mysql_select_db($db['name'], $con);
    mysql_set_charset($db['charset'], $con);
    if (!mysql_select_db($db['name'], $con)) {
        echo "Az adatbázis nem választható: " . mysql_error();
        exit;
    }
    return $con;
}

function closeDb($con) {
    mysql_close($con);
}

function insertTable($table, $vars, $con) {
    foreach ($vars as $key => $value) {
        $insert_list_variables[] = $key;
        if (is_numeric($value))
            $insert_list_values[] = "$value";
        else
            $insert_list_values[] = "'" . $value . "'";
    }

    $insert_list_variables = implode(", ", $insert_list_variables);
    $insert_list_values = implode(", ", $insert_list_values);
    $sql = "
			INSERT INTO
				" . $table . " (" . $insert_list_variables . ")
			VALUES
				(" . $insert_list_values . ")
		";
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
}


function insertDepoDb($deposit, $con) {
    global $db;
    $sql = "INSERT INTO  deposits (floor ,door ,area, residents_no, "
            . "area_ratio, resident_name) "
            . "values (\"{$deposit['floor']}\", \"{$deposit['door']}\", "
            . "\"{$deposit['area']}\","
            . "\"{$deposit['residents']}\", \"{$deposit['area_ratio']}\", "
            . "\"{$deposit['resident_name']}\")";
//echo $sql;
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo mysql_errno() . "(insert): " . mysql_error();
        exit();
    }
    $sql = "SELECT SUM(`deposits`.`area`)as t_a FROM `deposits`;";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno() . "(ta): " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $ta = $row['t_a'];
    }
    $sql = "UPDATE `{$db['name']}`.`fees` SET `dealer` = '$ta' where `multiplier` = '/terület-egység';";
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo mysql_errno() . "(db): " . mysql_error();
        exit();
    }
    $sql = "SELECT SUM(`residents_no`) as db from deposits;";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $dbs = $row['db'];
    }
    $sql = "UPDATE `{$db['name']}`.`fees` SET `dealer` = '$dbs' where `multiplier` = '/fő';";
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
}

function getDepositId($floor, $door) {
    $sql = "select id from deposits where floor=\"$floor\" and door=\"$door\";";
    $res = mysql_query($sql);
    if (!$res) {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0) {
        echo "Nincs ilyen albetét";
        exit;
    }
    while ($row = mysql_fetch_assoc($res)) {
        $id = $row["id"];
    }
    return $id;
}

function authUserDb($userdata, $con) {
    $sql = "select username from residents where username=\"{$userdata['user']}\" 
        and password=\"{$userdata['pass']}\" and active=1";
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit();
    }

    if (mysql_num_rows($res) == 0) {
        return false;
    } else {
        return true;
    }
}

function getUserRole($userdata) {
    $sql = "select admin from residents where username=\"{$userdata['user']}\";";
    $res = mysql_query($sql);
    if (!$res) {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0) {
        echo "Nincs ilyen felhasználó";
        exit;
    }
    while ($row = mysql_fetch_assoc($res)) {
        $role = $row["admin"];
    }
    return $role;
}

function getUserId($userdata) {
    $sql = "select id from residents where username=\"{$userdata['user']}\";";
    $res = mysql_query($sql);
    if (!$res) {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0) {
        echo "Nincs ilyen felhasználó";
        exit;
    }
    while ($row = mysql_fetch_assoc($res)) {
        $id = $row["id"];
    }
    return $id;
}

function getUserDepositId($userdata) {
    $sql = "select depositid from residents where username=\"{$userdata['user']}\";";
    $res = mysql_query($sql);
    if (!$res) {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0) {
        echo "Nincs ilyen felhasználó";
        exit;
    }
    while ($row = mysql_fetch_assoc($res)) {
        $depositId = $row["depositid"];
    }
    return $depositId;
}

function getAdminId($userdata) {
    $sql = "select id from admin where username=\"{$userdata['user']}\";";
    $res = mysql_query($sql);
    if (!$res) {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0) {
        echo "Nincs ilyen felhasználó";
        exit;
    }
    while ($row = mysql_fetch_assoc($res)) {
        $id = $row["id"];
    }
    return $id;
}

function authAdminDb($userdata, $con) {
    $sql = "select username from admin where username=\"{$userdata['user']}\" 
        and password=\"{$userdata['pass']}\"";
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit();
    }

    if (mysql_num_rows($res) == 0) {
        return false;
    } else {
        return true;
    }
}

function getAdminRole($userdata) {
    $sql = "select role from admin where username=\"{$userdata['user']}\";";
    $res = mysql_query($sql);
    if (!$res) {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0) {
        echo "Nincs ilyen felhasználó";
        exit;
    }
    while ($row = mysql_fetch_assoc($res)) {
        $role = $row["role"];
    }
    return $role;
}

function getADeposit($id) {
    $sql = "SELECT `id`, `floor`, `door`, `area`, "
            . "`residents_no`, `area_ratio`, "
            . "`resident_name` FROM `deposits` WHERE `id`=$id;";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    echo '<div class="content">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-list"></i> Albetét adatai</h3>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> id </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakás terület (nm) </th>
   <th> Lakók száma </th>
   <th> Lakás tulajdoni hányad </th>
   <th> Lakó neve </th>
</tr>

   
EOT;
    foreach ($table as $row) {
        echo '<tbody>';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value)) {
                echo '<td>' . str_replace(".", ",", round($value, 2)) . '</td>';
            } else {
                echo '<td>' . $value . '</td>';
            }
        }
        echo '</tr>';
        echo '</tbody>';
    }
    echo '</table>';
    echo '</div>';
    return $row;
}

function updateDepoDb($deposit, $con) {
    global $db;

    $sql = "UPDATE `{$db['name']}`.`deposits` SET `floor` = '{$deposit['floor']}', "
            . "`door` = '{$deposit['door']}',`area` = '{$deposit['area']}',"
            . "`residents_no` = '{$deposit['residents']}',"
            . "`area_ratio`= '{$deposit['area_ratio']}', "
            . "`resident_name` = '{$deposit['resident_name']}' "
            . "WHERE `deposits`.`id` = {$deposit['id']};";
//echo $sql;
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo "update deposit hiba -" . mysql_errno() . ": " . mysql_error();
        exit();
    }
    $sql = "SELECT SUM(`deposits`.`area`)as t_a FROM `deposits`;";
    $result = mysql_query($sql);
    if (!$result) {
        echo "select sum hiba -" . mysql_errno() . "(ta): " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $ta = $row['t_a'];
    }
    $sql = "UPDATE `{$db['name']}`.`fees` SET `dealer` = '$ta' where `multiplier` = '/terület-egység';";
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo "update dealer -" . mysql_errno() . ": " . mysql_error();
        exit();
    }
    $sql = "SELECT SUM(`residents_no`) as db from deposits;";
    $result = mysql_query($sql);
    if (!$result) {
        echo "select sumresidents -" . mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $dbs = $row['db'];
    }
    $sql = "UPDATE `{$db['name']}`.`fees` SET `dealer` = '$dbs' where `multiplier` = '/fő';";
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo "update fees -" . mysql_errno() . ": " . mysql_error();
        exit();
    }
}

function getMyDepo($id) {
    global $house;
    global $_SESSION;
    $sql = "SELECT `floor`, `door`, `area`,  "
            . "`residents_no`, `area_ratio`, "
            . "`resident_name` FROM `deposits` WHERE `id`=$id;";
    $result1 = mysql_query($sql);
    if (!$result1) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $deposit = array();
    while ($row = mysql_fetch_assoc($result1)) {
        $deposit = $row;
    }
    $sql = "SELECT * from fees;";
    $result2 = mysql_query($sql);
    if (!$result2) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $fees = array();
    while ($row = mysql_fetch_assoc($result2)) {
        $fees[] = $row;
    }
    $ccost = $fees[0];
    $grabage = $fees[1];

    $sumccost = 0;

    $ccost_base = round(($ccost['yearly_amount'] * $deposit['area']), 0);
    $ccost_cost = round_to_nearest_n($ccost_base, 50);
    $grabage_cost = round(($grabage['yearly_amount'] * $deposit['residents_no']), 0);

    $ccosts = $ccost_cost + $grabage_cost;


    echo <<<EOT
<div class="content">
<div id="mydepo">
        
<h3 class="primary"> Albetét adatai</h3>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakás terület (nm) </th>
   <th> Lakók száma </th>
   <th> Lakás tulajdoni hányad </th>
   <th> Lakó neve </th>
   <th>Havi közösköltség</th>
   
</tr>
   
EOT;
    echo '<tbody>';
    echo '<tr>';
    echo "<td>{$deposit['floor']}</td>";
    echo "<td>{$deposit['door']}</td>";
    echo '<td>' . str_replace(".", ",", round($deposit['area'], 2)) . ' m<sup>2</sup></td>';
    echo '<td>' . $deposit['residents_no'] . ' fő</td>';
    echo '<td>' . str_replace(".", ",", round($deposit['area_ratio'], 2)) . '</td>';
    echo '<td>' . $deposit['resident_name'] . '</td>';
    echo "<td class='tdwarning'>" . number_format($ccosts, 0, ',', ' ') . " Ft/hó</td>";
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo <<<EOT
    </div>
<hr />
<div id="ccost">
<h3 class="success"> Közösköltség részletezése </h3>
<table id="responsiveTableTwo" class="large-only" cellspacing="0">
<tr align="left" class="success">
   <th>Költségek</th>
   <th> {$ccost['name']} </th>
   <th> {$grabage['name']}  </th>
   <th>Összesen</th>
</tr>
<tbody>
<tr>
   <td>Megosztás módja</td>
   <td> {$ccost['multiplier']} </td>
   <td> {$grabage['multiplier']} </td>
   <td class='tdsuccess'> </td>
</tr>
EOT;
    echo "<tr>";
    echo "<td>Egységár</td>";
    echo "<td>" . number_format($ccost['yearly_amount'], 0, ',', ' ') . " Ft/m<sup>2</sup> </td>";
    echo "<td>" . number_format($grabage['yearly_amount'], 0, ',', ' ') . " Ft/fő </td>";
    echo "<td class='tdsuccess'> </td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Szorzó</td>";
    echo '<td>' . str_replace(".", ",", round($deposit['area'], 2)) . ' m<sup>2</sup> </td>';
    echo '<td>' . $deposit['residents_no'] . ' fő</td>';
    echo "<td class='tdsuccess'> </td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Közösköltség</td>";
    echo "<td>" . number_format($ccost_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($grabage_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td class='tdwarning'>" . number_format($ccosts, 0, ',', ' ') . " Ft/hó</td>";
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '<hr />';
    echo '<div>';
    getMyPayments($id);
    if (isset($_SESSION['userid'])) {
// ha userként lépsz be    
        $user = getUserData($_SESSION['userid']);

        echo <<<EOT
			<div class="content"><div class="row">
		<div class="col-md-8">
			<h3 class="primary">Személyes adatok</h3>


			<p class="lead">
				<img alt="Tenant Avatar" src="pics/user_avatar.png" class="avatar" />
				{$user['firstname']} {$user['lastname']}<br />
				{$user['email']}</p>
                        <p class="lead">{$house['name']}, {$deposit['floor']}/{$deposit['door']} lakás</p>
		</div>

		<div class="col-md-4">
	        <div class="list-group">
				<li class="list-group-item active">Motorház</li>
				<a data-toggle="modal" href="#ktgAlakul" class="list-group-item">Közösköltség alakulása</a>
				<a data-toggle="modal" href="#otherCost" class="list-group-item">Egyéb költségek listázása</a>
				<a data-toggle="modal" href="#newMsg" class="list-group-item">Üzenet küldése</a>
				<a data-toggle="modal" href="#editPassword" class="list-group-item">Jelszó módosítása</a>
	        </div>
		</div>
	</div>
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
						<button type="submit" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Küldés</button>
						<button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- -- Jelszo valtoztatas -- -->
	<div class="modal fade" id="editPassword" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header modal-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
					<h4 class="modal-title">Jelszó módosítása</h4>
				</div>
				<form action="passwordchange.php" method="post" 
                                    data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                                    data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                                    data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
					<div class="modal-body">
	                    <div class="form-group">
	                        <label for="pass1">Új jelszó</label>
	                        <input type="text" class="form-control" name="pass1" id="pass1" value="" data-bv-identical="true"
                                               data-bv-identical-field="pass2"
                                               data-bv-identical-message="A két jelszó nem egyezik"">
							<span class="help-block">Új jelszó</span>
	                    </div>
						<div class="form-group">
	                        <label for="pass2">Jelszó megerősítése</label>
	                        <input type="text" class="form-control" name="pass2" id="pass2" value="" data-bv-identical="true"
                                               data-bv-identical-field="pass1"
                                               data-bv-identical-message="A két jelszó nem egyezik"/>
                                <input type="hidden" class="form-control" name="id" id="id" value="{$_SESSION['userid']}" />
							<span class="help-block">Adja meg újra jelszavát. A megadott jelszavaknak egyezniük kell!</span>
	                    </div>
					</div>
					<div class="modal-footer">
						<button type="submit" value="editPassword" class="btn btn-success btn-icon"><i class="fa fa-check-square-o"></i> Küldés</button>
						<button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
					</div>
				</form>
			</div>
		</div>
	</div>
        <!-- -- Kozoskoltseg Modal -- -->
	<div class="modal fade" id="ktgAlakul" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header modal-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
					<h4 class="modal-title">Közösköltség alakulása az utolsó 12 hónapban</h4>
				</div>
				<div class="modal-body">
EOT;
        getMyAllCcost($id);
        echo <<<EOT
				</div>
				<div class="modal-footer">
                                    <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Bezár</button>
				</div>
			</div>
		</div>
	</div>
        <!-- -- Tovabbi koltseg Modal -- -->
	<div class="modal fade" id="otherCost" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header modal-primary">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
					<h4 class="modal-title">További, az albetétre terhelt költségek</h4>
				</div>
				<div class="modal-body">
EOT;
        getMyAllOcost($id);
        echo <<<EOT
				</div>
				<div class="modal-footer">
                                    <button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Bezár</button>
				</div>
			</div>
		</div>
	</div>
EOT;
    } else {     //ha adminként lépsz be
        echo '<a data-toggle="modal" href="#payment" class="btn btn-success btn-icon"><i class="fa fa-dollar"></i>Új befizetés rögzítése</a>';
        //echo "<a href=\"payment.php?id=" . $id . "\"><button class='btn btn-success btn-icon'><i class='fa fa-dollar'></i>Új befizetés rögzítése</button></a>";
        echo "<hr />";
        echo '<a data-toggle="modal" href="#cost" class="btn btn-success btn-icon"><i class="fa fa-history"></i>Új költség rögzítése</a>';
        //echo "<a href=\"ocost.php?id=" . $id . "\"><button class='btn btn-success btn-icon'><i class='fa fa-history'></i>Új költség rögzítése</button></a>";
        echo "<hr />";
        echo '<h3 class="primary"><i class="fa fa-dollar"></i> Közösköltség alakulása az utolsó 12 hónapban</h3>';
        getMyAllCcost($id);
        echo "<hr />";
        echo '<h3 class="primary"><i class="fa fa-history"></i> További előírt költségek</h3>';
        getMyAllOcost($id);
    }
    echo '</div>';
    echo '</div>';
    echo <<<EOT
		<!-- -- Uj befizetes Modal -- -->
		<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header modal-primary">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h4 class="modal-title">Új könyvelt befizetés rögzítése</h4>
					</div>
					<form action="insertpayment.php" method="post"
                                         data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                                         data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                                         data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
						<div class="modal-body">
							<div class="form-group">
                                        <p>Lakás: {$deposit['floor']} / {$deposit['door']}. ajtó</p>
                                        
								
		                    </div>
				   
                                        <div class="form-group">
	                        <label for="payment">Összeg</label> 
                              <input type="text" id="payment" name="payment" class="form-control" data-bv-notempty="true"
                                               data-bv-notempty-message="Adjon meg egy összeget!"
                                               data-bv-regexp="true"
                                               data-bv-regexp-regexp="^[0-9]+$"
                                               data-bv-regexp-message="Csak számokat írjon be!">
							<span class="help-block">Befizetés összege</span>
	                    </div>
						<div class="form-group">
	                        <label for="account_date">Könyvelés dátuma</label> 
                                <input type="text" class="form-control" name="account_date" id="account_date" placeholder="2000.01.01"
                                data-bv-notempty="true"
                                data-bv-notempty-message="A dátum kitöltése kötelező!"

                                data-bv-date="true"
                                data-bv-date-format="YYYY.MM.DD"
                                data-bv-date-separator="."
                                data-bv-date-message="A formátum nem megfelelő">
                              <input type="hidden" id="did" name="did" value="$id">
							<span class="help-block">Kérem, "éééé.hh.nn" formátumot használjon!</span>
	                    </div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success btn-icon"><i class="fa fa-dollar"></i> Befizetés rögzítése</button>
							<button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
						</div>
					</form>
				</div>
			</div>
		</div>		

EOT;
                                        
echo <<<EOT

		<!-- -- Uj koltseg Modal -- -->
		<div class="modal fade" id="cost" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header modal-primary">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
						<h4 class="modal-title">Új költség rögzítése</h4>
					</div>
					<form action="insertocost.php" method="post"
                                         data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                                         data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                                         data-bv-feedbackicons-validating="glyphicon glyphicon-refresh"/>
						<div class="modal-body">
							<div class="form-group">
                                        <p>Lakás: {$deposit['floor']} / {$deposit['door']}. ajtó</p>
                                        
								
		                    </div>
				   
                                        <div class="form-group">
	                        <label for="cost">Összeg</label>
                              <input type="text" id="cost" name="cost" class="form-control" data-bv-notempty="true"
                                               data-bv-notempty-message="Adjon meg egy összeget!"
                                               data-bv-regexp="true"
                                               data-bv-regexp-regexp="^[0-9]+$"
                                               data-bv-regexp-message="Csak számokat írjon be!" />
							<span class="help-block">Költség mértéke</span>
	                    </div>
						<div class="form-group">
	                        <label for="title">Jogcím</label> 
                                <input type="text" id="title" name="title" class="form-control" data-bv-notempty="true"
                                               data-bv-notempty-message="Adja meg a költség jogcímét!">
	                    </div>
                                        <div class="form-group">
	                    <label for="date">Rögzítés dátuma</label>
                            <input type="text" id="date" name="date" class="form-control" placeholder="2000.01.01"
                                data-bv-notempty="true"
                                data-bv-notempty-message="A dátum kitöltése kötelező!"

                                data-bv-date="true"
                                data-bv-date-format="YYYY.MM.DD"
                                data-bv-date-separator="."
                                data-bv-date-message="A formátum nem megfelelő">
                            <span class="help-block">Kérem, "éééé.hh.nn" formátumot használjon!</span>
                            <input type="hidden" id="did" name="did" value="$id">
	                    </div>
								<div class="alertMsg danger"><i class="fa fa-warning"></i> Figyelem! Azonnali könyvelés - a bejegyzés nem törölhető!</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success btn-icon"><i class="fa fa-dollar"></i> Költség rögzítése</button>
							<button type="button" class="btn btn-warning btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Mégsem</button>
						</div>
					</form>
				</div>
			</div>
		</div>		

EOT;
}

function getAllDepo() {
    $year = date('Y');

    $sql = "SELECT `id`, `floor`, `door`, `area`, "
            . "`residents_no`, `area_ratio`, "
            . "`resident_name` FROM `deposits`;";
    $result1 = mysql_query($sql);
    if (!$result1) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $deposit = array();
    $deprows = mysql_num_rows($result1);
    while ($row = mysql_fetch_assoc($result1)) {
        $deposit[] = $row;
    }
    $sql = "SELECT * from fees;";
    $result2 = mysql_query($sql);
    if (!$result2) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $fees = array();
    while ($row = mysql_fetch_assoc($result2)) {
        $fees[] = $row;
    }
    $sql = "SELECT * from deposit_balance where year=$year";
    $result3 = mysql_query($sql);
    if (!$result3) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $balance = array();
    while ($row = mysql_fetch_assoc($result3)) {
        $balance[] = $row;
    }
    $ccost = $fees[0];
    $grabage = $fees[1];



    $sumarea = $sumresidents = $sumarearatio = $sumccost = $sumbalance = 0;
    for ($i = 0; $i < $deprows; $i++) {

        $ccost_base = round(($ccost['yearly_amount'] * $deposit[$i]['area']), 0);
        $ccost_cost = round_to_nearest_n($ccost_base, 50);
        $grabage_cost = round(($grabage['yearly_amount'] * $deposit[$i]['residents_no']), 0);


        $deposit[$i]["ccost"] = $ccost_cost + $grabage_cost;
        $deposit[$i]["balance"] = getActualBalance($balance[$i]['actual_balance'], $deposit[$i]["id"]);
        $sumarea += $deposit[$i]['area'];
        $sumresidents += $deposit[$i]['residents_no'];
        $sumarearatio += $deposit[$i]['area_ratio'];
        $sumccost +=$deposit[$i]["ccost"];
        $sumbalance += $deposit[$i]["balance"];
    }

    echo '<div class="content" id="section-to-print">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-list"></i> Albetétek - lista</h3>
<a href="#" class="btn btn-primary btn-icon no-print" onclick="jQuery.print('#section-to-print')"><i class="fa fa-print"></i> Nyomtatás</a>    

    <div class="container">
        <div class="row primaryth">


            <div class="col-md-1"> Emelet / Ajtó </div>
            <div class="col-md-1" style='text-align:right;'> Terület </div>
            <div class="col-md-1" style='text-align:right;'> Lakók </div>
            <div class="col-md-1 tool-tip" title="Lakás tulajdoni hányad" style='text-align:right;'>Lakás th </div>
            <div class="col-md-2"> Lakó neve </div>
            <div class="col-md-1 tool-tip" title="Közösköltség" >Közösktg. </div>
            <div class="col-md-1" style="text-align:right;"> Egyenleg </div>
            <div class="col-md-1" > Részletek </div>
            <div class="col-md-1 tool-tip" title="Új könyvelt befizetés rögzítése"> Befizetés </div>
            <div class="col-md-1 tool-tip" title="Új költség rögzítése"> Új költség </div>
            <div class="col-md-1"> Módosítás </div>

        </div>
   
EOT;
    foreach ($deposit as $row) {
        echo '<div class="row" style="font-size:0.8em">';
        echo "<div class='col-md-1'> {$row['floor']} / {$row['door']}</div>";
        echo "<div class='col-md-1' style='text-align:right;'>" . str_replace(".", ",", round($row['area'], 2)) . " m<sup>2</div>";
        echo "<div class='col-md-1' style='text-align:right;'>" . str_replace(".", ",", round($row['residents_no'], 2)) . " fő</div>";
        echo "<div class='col-md-1' style='text-align:right;'>" . str_replace(".", ",", round($row['area_ratio'], 2)) . "</div>";
        echo "<div class='col-md-2'>" . $row['resident_name'] . "</div>";
        echo "<div class='col-md-1' style='text-align:right;'>" . number_format($row['ccost'], 0, ',', ' ') . " Ft</div>";
        echo "<div class='col-md-1' style='text-align:right;'>" . number_format($row['balance'], 0, ',', ' ') . " Ft</div>";
        echo "<div class='col-md-1'><a href=\"mydepo.php?depositid=" . $row['id'] . "\">Részletek</a></div>";
        payment($row);
        oCost($row);
        updateDepo($row);
        echo '</div>';
        }
    
    echo '<div class="row" style="font-size:0.8em">';
    echo '<div class="col-md-1" style="background:#d9534f; color:#ffffff;">Összesen:</div>';
    echo '<div class="col-md-1" style="text-align:right; background:#336699; color:#ffffff;">' . number_format($sumarea, 0, ',', ' ') . ' m<sup>2</sup></div>';
    echo "<div class='col-md-1' style='text-align:right; background:#336699; color:#ffffff;'>$sumresidents fő</div>";
    echo "<div class='col-md-1' style='text-align:right; background:#336699; color:#ffffff;'>" . number_format(str_replace(".", ",", round($sumarearatio, 2)), 0, ',', ' ') . "</div>";
    echo '<div class="col-md-2" style="background:#336699;">&nbsp;</div>';
    echo "<div class='col-md-1' style='text-align:right; background:#336699; color:#ffffff;'>" . number_format($sumccost, 0, ',', ' ') . " Ft</div>";
    echo "<div class='col-md-1' style='text-align:right; background:#336699; color:#ffffff;'>" . number_format($sumbalance, 0, ',', ' ') . " Ft</div>";
    echo '<div id="col4"class="col-md-4" style="background:#336699;">&nbsp;</div>';
    echo '</div>';
    echo '</div>';
   
    
    
    echo '</div>';
}

function listResidents() {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT `residents`.`id`,`residents`.`firstname`,`residents`.`lastname`,"
            . "`residents`.`email`, `residents`.`phone`, `residents`.`username`,`deposits`.`floor`,"
            . "`deposits`.`door`,`residents`.`active`, `residents`.`last_login` "
            . "FROM residents LEFT JOIN `deposits` ON `residents`.`depositid` = `deposits`.`id` "
            . "ORDER BY `deposits`.`door` ASC";

    $result = mysql_query($sql);
    if (!$result) {
        die("listResidents hiba:" . mysql_errno() . " - " . mysql_error());
    }
    if (mysql_num_rows($result) != 0) {
        $table = array();
        while ($row = mysql_fetch_assoc($result)) {
            $table[] = $row;
        }
        for ($i = 0; ($i < mysql_num_rows($result)); $i++) {
            if ($table[$i]['active'] == 1) {
                $table[$i]['active'] = "aktív";
            } else {
                $table[$i]['active'] = "nem aktív";
            }
        }
        echo '<div class="content">';
        echo '<h3 class="primary"><i class="fa fa-users"></i>   </h3>';
        echo '<table id="responsiveTable" class="large-only" cellspacing="0">';
        echo <<<EOT
   <tr align="left" class="primary">
   <th> ID </th>
   <th> Név </th>
   <th> e-mail cím</th>
   <th> telefonszám</th>
   <th> Felhasználónév</th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Aktív </th> 
   <th> Utolsó belépés </th> 
   <th> Státusz módosítása  </th>
   <th> Módosítás </th>
   <th> Új jelszó </th>
   <th> Törlés</th>
   </tr>
EOT;
        echo "<tbody>";
        foreach ($table as $row) {

            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
            sendMessageToUser($row['email'], $row['id']);
            echo '<td>' . $row['phone'] . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['floor'] . '</td>';
            echo '<td>' . $row['door'] . '</td>';
            echo '<td>' . $row['active'] . '</td>';
            echo '<td>' . $row['last_login'] . '</td>';
//            foreach ($row as $value) {
//                echo "<td>$value</td>";
//            }
            if ($row['active'] == "aktív") {
                echo "<td><a id=\"alink\" href=\"update_ustatus.php?uid={$row['id']}"
                . "&status=1\">Letiltás</a></td>";
            } else {
                echo "<td><a id=\"alink\" href=\"update_ustatus.php?uid={$row['id']}"
                . "&status=0\">Aktiválás</a></td>";
            }
            updateUser($row);
            updatePassword($row);
            kill($row);
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'Még nem vett fel lakókat. Vegyen fel egyet!';
    }
}

function changeUserSatus($id, $status) {
    switch ($status) {
        case "0":
            $sql = "UPDATE  `residents` SET  `active` =  '1' WHERE  `residents`.`id` =$id;";
            break;
        case "1":
            $sql = "UPDATE  `residents` SET  `active` =  '0' WHERE  `residents`.`id` =$id;";
            break;
    }
    $res = mysql_query($sql);
    if (!$res) {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function changeUserPassword($id, $password) {
    $sql = "UPDATE  `residents` SET  `password` =  '$password' WHERE  `residents`.`id` =$id;";
    $res = mysql_query($sql);
    if (!$res) {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
    return $res;
}

function changeAdminSatus($id, $status) {
    switch ($status) {
        case "0":
            $sql = "UPDATE  `residents` SET  `admin` =  '1' WHERE  `residents`.`id` =$id;";
            break;
        case "1":
            $sql = "UPDATE  `residents` SET  `admin` =  '0' WHERE  `residents`.`id` =$id;";
            break;
    }
    $res = mysql_query($sql);
    if (!$res) {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function getUserData($id) {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT `residents`.`id`,`residents`.`firstname`,`residents`.`lastname`,"
            . "`residents`.`email`, `residents`.`phone`, `residents`.`username`,`deposits`.`floor`,"
            . "`deposits`.`door` FROM residents LEFT JOIN `deposits` "
            . "ON `residents`.`depositid` = `deposits`.`id` WHERE `residents`.`id` = $id;";
    $result = mysql_query($sql);
    if (!$result) {
        die("getUserData hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $array = array();
    while ($row = mysql_fetch_assoc($result)) {
        $array = $row;
    }
    return $array;
}

function getBaseData() {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT `name`, `yearly_amount`, `multiplier` from fees;";
    $result = mysql_query($sql);
    if (!$result) {
        die("getBaseData hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $array = array();
    while ($row = mysql_fetch_assoc($result)) {
        $array[] = $row;
    }

    echo '<div class="content">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-book"></i> Alapköltségek </h3>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> Költség típusa </th>
   <th> Alapdíj </th>
   <th> Elosztás módja </th>
</tr>
EOT;
    foreach ($array as $row) {

        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value)) {
                echo "<td>" . number_format($value, 0, ',', ' ') . " Ft</td>";
            } else {
                echo "<td>$value </td>";
            }
        }
        echo '</tr>';
    }
    echo '</table>';
//print_r($array);
    return $array;
}

function updatebase($data) {
    global $db;
    $sql = "UPDATE `{$db['name']}`.`fees` SET `yearly_amount` = {$data['ccost']} WHERE `fees`.`id` = 1;";
    $result = mysql_query($sql);
    if (!$result) {
        die("update ccost hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $sql = "UPDATE `{$db['name']}`.`fees` SET `yearly_amount` = {$data['grabage']} WHERE `fees`.`id` = 2;";
    $result = mysql_query($sql);
    if (!$result) {
        die("update grabage cost hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function listAdmins() {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT id, username, email, role from admin where id > 1;";

    $result = mysql_query($sql);
    if (!$result) {
        die("listAdmins hiba:" . mysql_errno() . " - " . mysql_error());
    }
    if (mysql_num_rows($result) != 0) {
        $table = array();
        while ($row = mysql_fetch_assoc($result)) {
            $table[] = $row;
        }
        for ($i = 0; ($i < mysql_num_rows($result)); $i++) {
            if ($table[$i]['role'] == 1) {
                $table[$i]['role'] = "Közösképviselő";
            } else {
                $table[$i]['role'] = "Adminisztrátor";
            }
        }
        echo '<div class="content">';
        echo '<h3 class="primary"><i class="fa fa-users"></i> Adminisztrátorok </h3>';
        echo '<table id="responsiveTable" class="large-only" cellspacing="0">';
        echo <<<EOT
   <tr align="left" class="primary">
   <th> ID </th>
   <th> Username </th>
   <th> E-mail </th>
   <th> Szerepkör</th>
   <th> Jelszó módosítás </th>
   <th> Szerepkör módosítása </th>
   <th> Admin törlése </th>
   </tr>
EOT;
        echo "<tbody>";
        foreach ($table as $row) {

            echo '<tr>';
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo <<<EOT
      
        <td><a href="update_apassword.php?uid={$row['id']}">Jelszó módosítás</a></td>
EOT;
            if ($row['role'] == "Közösképviselő") {
                echo "<td><a id=\"alink\" href=\"update_arole.php?uid={$row['id']}"
                . "&status=0\">Szerepkör módosítása</a></td>";
            } else {
                echo "<td><a id=\"alink\" href=\"update_arole.php?uid={$row['id']}"
                . "&status=1\">Szerepkör módosítása</a></td>";
            }

            echo "<td><a href=\"killadmin.php?uid={$row['id']}\">Admin törlése</a></td>";

            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo 'Nincsenek adminisztrátorok. Vegyen fel egyet!';
    }
}

function changeAdminRole($id, $status) {
    switch ($status) {
        case "0":
            $sql = "UPDATE  `admin` SET  `role` =  '99' WHERE  `admin`.`id` =$id;";
            break;
        case "1":
            $sql = "UPDATE  `admin` SET  `role` =  '1' WHERE  `admin`.`id` =$id;";
            break;
    }
    $res = mysql_query($sql);
    if (!$res) {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function changeAdminPassword($id, $password) {
    $sql = "UPDATE  `admin` SET  `password` =  '$password' WHERE  `admin`.`id` =$id;";
    $res = mysql_query($sql);
    if (!$res) {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
    return $res;
}

function getAdminData($id) {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT id, username, email, role from admin where id = $id;";

    $result = mysql_query($sql);
    if (!$result) {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table = $row;
    }
    return $table;
}

function killAdmin($id) {
    $sql = "DELETE from admin where id=$id;";
    $result = mysql_query($sql);
    if (!$result) {
        die("killAdmin Hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function killUser($id) {
    $sql = "DELETE from residents where id=$id;";
    $result = mysql_query($sql);
    if (!$result) {
        die("killUser Hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function getCcost($id) {
    $sql = "SELECT `floor`, `door`, `area`,  "
            . "`residents_no`, `area_ratio`, "
            . "`resident_name` FROM `deposits` WHERE `id`=$id;";
    $result1 = mysql_query($sql);
    if (!$result1) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $deposit = array();
    while ($row = mysql_fetch_assoc($result1)) {
        $deposit = $row;
    }
    $sql = "SELECT * from fees;";
    $result2 = mysql_query($sql);
    if (!$result2) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $fees = array();
    while ($row = mysql_fetch_assoc($result2)) {
        $fees[] = $row;
    }
    $ccost = $fees[0];
    $grabage = $fees[1];

    $sumccost = 0;

    $ccost_base = round(($ccost['yearly_amount'] * $deposit['area']), 0);
    $ccost_cost = round_to_nearest_n($ccost_base, 50);
    $grabage_cost = round(($grabage['yearly_amount'] * $deposit['residents_no']), 0);

    $ccosts = $ccost_cost + $grabage_cost;

    return $ccosts;
}

function getCurrentBalance($id) {
    $year = date("Y");
    $sql = "SELECT `actual_balance` FROM `deposit_balance` WHERE `deposit_id` = '$id' AND `year` = '$year'";
    $result = mysql_query($sql);
    if (!$result) {
        die("getCurrentBalance hiba:" . mysql_errno() . " - " . mysql_error());
    }
    if (mysql_num_rows($result) == 0) {
        echo "<span style='color: red; font-weight: bold;'>Figyelem! Az előző év könyvelése még nem zárult le, addig folyószámla adatai nem pontosak!</span>";
        //exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $balance = $row["actual_balance"];
    }
    return $balance;
}

function insertPayment($data, $user) {
    global $db;
    $year = date('Y');
    $oldbalance = getCurrentBalance($data['id']);
    $newbalance = $oldbalance + $data['payment'];
//    echo $oldbalance;
//    echo "<br>";
//    echo $newbalance;
    echo "<br>";
    $sql = "INSERT INTO payment (`deposit_id`, `date`, `amount`, `user`, `account_date`) "
            . "VALUES ({$data['id']}, CURDATE(), {$data['payment']}, '$user', '{$data['account_date']}')";
//    echo $sql;
    $result = mysql_query($sql);
    if (!$result) {
        die("insertIntoPayment hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $sql = "UPDATE `{$db['name']}`.`deposit_balance` SET `actual_balance` = '$newbalance' "
            . "WHERE `deposit_balance`.`deposit_id` = {$data['id']} AND `year` = $year";
//echo $sql;
    $result = mysql_query($sql);
    if (!$result) {
        die("updateCurrentBalance hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function getMyPayments($id) {
    global $db;
    $closing_balance = getCurrentBalance($id);
    $balance = getActualBalance($closing_balance, $id);
    if ($balance < 0) {
        $abalance = $balance * -1;
    } else {
        $abalance = $balance;
    }
    $abalance = number_format($abalance, 0, ',', ' ');
    $sql = "SELECT `deposits`.`floor`,`deposits`.`door`,`payment`.`account_date`,`payment`.`amount` FROM deposits "
            . "LEFT JOIN `{$db['name']}`.`payment` ON `deposits`.`id` = `payment`.`deposit_id` "
            . "WHERE `deposits`.`id` = $id "
            . "ORDER BY `payment`.`account_date` DESC LIMIT 25;";
//echo $sql;
    $result = mysql_query($sql);
    if (!$result) {
        die("getMyPayment hiba:" . mysql_errno() . " - " . mysql_error());
    }

    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }


    if ($balance < 0) {
        echo <<<EOT
                <div class="alertMsg warning"><i class="fa fa-warning"></i> Az Ön közösköltségének aktuális egyenlege: <span class="floatLeft">$abalance Ft elmaradás</span></div>
EOT;
    } else {
        echo <<<EOT
                <div class="alertMsg success"><i class="fa fa-info-circle"></i> Az Ön közösköltségének aktuális egyenlege: <span class="floatLeft">$abalance Ft túlfizetés</span></div> 
EOT;
    }
    if ($table[0]['account_date'] == NULL) {
        echo "Önnek nincs lekönyvelt befizetése.<br>"
        . "Felhívjuk figyelmét, hogy a befizetések azok beérkezése után 5-15 nappal kerülnek könyvelésre!";
    } else {
        echo '<button id="pays" value="Befizetesek" class="btn btn-success btn-icon"><i class="fa fa-bars"></i>Befizetések részletesen</button>';
        echo '<div id=payments>';
        echo '<h3 class="primary"><i class="fa fa-dollar"></i> Könyvelt befizetések </h3>';
        echo '<table id="responsiveTable" class="large-only" cellspacing="0">';
        echo <<<EOT
   <tr align="left" class="primary">
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Könyvelés dátuma</th>
   <th> Befizetés összege</th>
   </tr>
EOT;
        echo "<tbody>";
        foreach ($table as $row) {

            echo '<tr>';
            echo "<td>{$row['floor']}</td>";
            echo "<td>{$row['door']}</td>";
            echo "<td>{$row['account_date']}</td>";
            echo "<td>" . number_format($row['amount'], 0, ',', ' ') . " Ft</td>";



            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '<p>Felhívjuk figyelmét, hogy a befizetések azok beérkezése után 5-15 nappal kerülnek könyvelésre!</p>';
        echo '</div>';
    }
    echo '<hr/>';
}

function lastYear() {
    $sql = "SELECT max(`year`) as year FROM `deposit_balance`";
    $result = mysql_query($sql);
    if (!$result) {
        die("lastYear hiba:" . mysql_errno() . " - " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($result)) {
        $year = $row["year"];
    }
    return $year;
}

function startNewYear($lastyear) {
    $newyear = $lastyear + 1;
    $sql = "SELECT * FROM `deposit_balance` where year=$lastyear";
    $result = mysql_query($sql);
    if (!$result) {
        die("allDepositBalance hiba:" . mysql_errno() . " - " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($result)) {
        $balances[] = $row;
    }

    $balrows = mysql_num_rows($result);
    for ($i = 0; $i < $balrows; $i++) {
        $ccost = getCcost($balances[$i]['deposit_id']);
        $req_payment = $ccost * 12;
        $balance = $balances[$i]['actual_balance'] - $req_payment;
        $sql = "INSERT INTO deposit_balance (`deposit_id`, `year`, `opening_balance`, `actual_balance`)"
                . "VALUES ({$balances[$i]['deposit_id']}, $newyear, $balance, $balance)";
        $result = mysql_query($sql);
        if (!$result) {
            die("insertDepositBalance hiba:" . mysql_errno() . " - " . mysql_error());
        }
    }
}

function deleteFileFromDb($file) {
    $sql = "DELETE from documents WHERE `name`='$file'";
    $result = mysql_query($sql);
    if (!$result) {
        die("deleteFileFromDb hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function listDocuments() {
    $sql = "SELECT `name`, `shortname`, `description` FROM documents";
    $result = mysql_query($sql);
    if (!$result) {
        die("listDocuments hiba:" . mysql_errno() . " - " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($result)) {
        $docs[] = $row;
    }
    if (mysql_num_rows($result) != 0) {
        echo '<div class="content">';
        echo '<h3 class="primary"><i class="fa fa-file-pdf-o"></i> Fontos dokumentumok </h3>';
        foreach ($docs as $row) {
            echo "<h4><a href=\"./documents/{$row['name']}\" target=\"_blank\">{$row['shortname']}</a><br>"
            . "<span class=\"help-block\">{$row['description']}</span></h4>";
        }
        echo "</div>";
    }
}

function insertBoardMessage($msg) {
    if ($msg['valid_till'] != NULL) {
        $sql = "INSERT into board (`creation_date`, `title`, `text`, `valid_till`) "
                . "VALUES (CURDATE(), '{$msg['title']}', '{$msg['text']}', '{$msg['valid_till']}');";
    } else {
        $sql = "INSERT into board (`creation_date`, `title`, `text`) "
                . "VALUES (CURDATE(), '{$msg['title']}', '{$msg['text']}');";
    }
    $result = mysql_query($sql);
    if (!$result) {
        die("insertBoardMessage hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function getAllBoardMessages() {
    $sql = "SELECT * from board;";
    $result = mysql_query($sql);
    if (!$result) {
        die("listDocuments hiba:" . mysql_errno() . " - " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($result)) {
        $msg[] = $row;
    }
//    print_r($msg);
    if (mysql_num_rows($result) != 0) {
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            if ($msg[$i]['valid'] == 1) {
                $msg[$i]['valid'] = "aktív";
            } else {
                $msg[$i]['valid'] = "inaktív";
            }
        }
        echo "<br>";
//        print_r($msg);
        echo '<div class="content">';
        echo '<h3 class="primary"><i class="fa fa-book"></i> Hírek </h3>';
        echo <<<EOT
        <table id="responsiveTable" class="large-only" cellspacing="0">
            <tr align="left" class="primary">
                <th> id </th>
                <th> Feltöltés ideje </th>
                <th> Cím </th>
                <th> Törzs</th>
                <th> Érvényességi idő </th>
                <th> Érvényes </th>
                <th> Érvényesség változtatása </th>
            </tr>
            <tbody>
EOT;
        foreach ($msg as $row) {
            echo '<tr>';
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            if ($row['valid'] == "aktív") {
                echo "<td><a href='changemessagevalid.php?act=1&id={$row['id']}'>Inaktiválás</a></td>";
            } else {
                echo "<td><a href='changemessagevalid.php?act=0&id={$row['id']}'>Aktiválás</a></td>";
            }
            echo '</tr>';
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
}

function getLatestBoardMessage() {
    $sql = "SELECT * FROM `board`  WHERE `valid` = 1 AND (`valid_till`> CURDATE()) ORDER BY `id` DESC LIMIT 1";
    $result = mysql_query($sql);
    if (!$result) {
        die("getLatestBoardMessage hiba:" . mysql_errno() . " - " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($result)) {
        $msg = $row;
    }
    if (mysql_num_rows($result) != 0) {
        return $msg;
    }
}

function changeMsgStatus($id, $act) {
    switch ($act) {
        case "0":
            $sql = "UPDATE  `board` SET  `valid` =  '1' WHERE  `board`.`id` =$id;";
            break;
        case "1":
            $sql = "UPDATE  `board` SET  `valid` =  '0' WHERE  `board`.`id` =$id;";
            break;
    }
    $res = mysql_query($sql);
    if (!$res) {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function allBoardMessage() {
    $sql = "SELECT * FROM `board`  WHERE `valid` = 1 AND (`valid_till`> CURDATE()) ORDER BY `id` DESC";
    $result = mysql_query($sql);
    if (!$result) {
        die("allBoardMessage hiba:" . mysql_errno() . " - " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($result)) {
        $msg[] = $row;
    }
    if (mysql_num_rows($result) != 0) {
        echo '<div class="content">';
        echo <<<EOT
        <div class="panel panel-primary">
		<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-bullhorn"></i> Legfrisebb hírek</h3>
		</div>
EOT;

        foreach ($msg as $row) {
            echo "<div class='panel-body'><p>{$row['title']}<span style='float: right'>{$row['creation_date']}</span></p>
		<p>{$row['text']}</p></div><hr />";
        }
        echo "</div>";
    }
}

function getActualBalance($actual_balance, $id) {
    $year = date('Y');
    $con = connectDb();
    $sql = "SELECT ccost from ccost WHERE `deposit_id`=$id and `year`=$year ";
//echo $sql;
    $result = mysql_query($sql);
    if (!$result) {
        die("getCcost hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $ccost = array();
    while ($row = mysql_fetch_assoc($result)) {
        $ccost[] = $row;
    }
//print_r($ccost);
    $req_payment = 0;
    for ($i = 0; ($i < mysql_num_rows($result)); $i++) {
        $req_payment += $ccost[$i]['ccost'];
    }


    $balance = $actual_balance - $req_payment;
    return $balance;
}

function getAllCcost($id, $year) {
    $sql = "SELECT ccost from ccost WHERE `deposit_id`=$id and `year`=$year ";
//echo $sql;
    $result = mysql_query($sql);
    if (!$result) {
        die("getAllCcost hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $ccost = array();
    while ($row = mysql_fetch_assoc($result)) {
        $ccost[] = $row;
    }
//print_r($ccost);
    $all_ccost = 0;
    for ($i = 0; ($i < mysql_num_rows($result)); $i++) {
        $all_ccost += $ccost[$i]['ccost'];
    }
    return $all_ccost;
}

function getMyAllCcost($id) {
    global $db;
    $sql = "SELECT `deposits`.`floor`,`deposits`.`door`,`ccost`.`year`,`ccost`.`month`,`ccost`.`ccost` FROM deposits "
            . "LEFT JOIN `{$db['name']}`.`ccost` ON `deposits`.`id` = `ccost`.`deposit_id` WHERE `deposits`.`id` = $id 
        ORDER BY `ccost`.`year` DESC, `ccost`.`month` DESC LIMIT 12";
    $result = mysql_query($sql);
    if (!$result) {
        die("geMyAllCcost hiba:" . mysql_errno() . " - " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($result)) {
        $ccost[] = $row;
    }
    if ($ccost[0]['ccost'] == 0) {
        echo "Nincsen közösköltség az albetétre terhelve.";
    } else {
        echo <<<EOT
        <table id="responsiveTable" class="large-only" cellspacing="0">
            <tr align="left" class="primary">
                <th> Emelet </th>
                <th> Ajtó </th>
                <th> Év </th>
                <th> Hónap </th>
                <th> Közösköltség</th>
            </tr>
            <tbody>
EOT;
        foreach ($ccost as $row) {
            echo '<tr>';
            foreach ($row as $value) {
                if (is_numeric($value) && $value > 2099) {
                    echo "<td>" . number_format($value, 0, ',', ' ') . " Ft</td>";
                } else {
                    echo "<td>$value</td>";
                }
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
}

function getAllAccounts($year) {
    global $house;
    $date = date("Y.m.d");
    $sql = "SELECT `id`, `floor`, `door`, "
            . "`resident_name` FROM `deposits`;";
    $result1 = mysql_query($sql);
    if (!$result1) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $deposit = array();
    $deprows = mysql_num_rows($result1);
    while ($row = mysql_fetch_assoc($result1)) {
        $deposit[] = $row;
    }

    $sql = "SELECT * from deposit_balance where year='$year'";
    $result2 = mysql_query($sql);
    if (!$result2) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    if (mysql_num_rows($result2) == 0) {
        echo <<<EOT
        <div class="buttons btn-back">
	   <form action="stat.php">
	   <button type="submit" class="btn btn-success btn-icon value="Vissza" ><i class="fa fa-arrow-circle-left"></i>Vissza</button>
	   </form>
        </div>
        <div class="content">
        Sajnos nincs $year. évre megjelníthető adat.
        </div>           
EOT;
        exit();
    }
    $balance = array();
    while ($row = mysql_fetch_assoc($result2)) {
        $balance[] = $row;
    }
    $allobalance = $allccost = $allpayment = $allbalance = 0;
    for ($i = 0; $i < mysql_num_rows($result1); $i++) {
        $id = $deposit[$i]['id'];
//nyitó egyenleg
        $deposit[$i]['opening_balance'] = $balance[$i]['opening_balance'];
//összes fizetendő
        $deposit[$i]['ccost'] = getAllCcost($id, $year);
//összes befizetés
        $deposit[$i]['payment'] = getAllPayment($id, $year);
//egyenleg
        $deposit[$i]['balance'] = ($deposit[$i]['opening_balance'] - $deposit[$i]['ccost']) + $deposit[$i]['payment'];
        $allobalance += $deposit[$i]['opening_balance'];
        $allccost += $deposit[$i]['ccost'];
        $allpayment += $deposit[$i]['payment'];
        $allbalance += $deposit[$i]['balance'];
    }
    echo '<div class="content" id="section-to-print">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-list"></i> Éves kimutatás a(z) {$house['name']} szám alatti házra, $year. évre</h3>
<h4 class="primary"> Készült: $date </h4>
<div>
<a href="#" class="btn btn-primary btn-icon no-print" onclick="jQuery.print('#section-to-print')"><i class="fa fa-print"></i> Nyomtatás</a>
</div>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> id </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakó neve </th>
   <th> Nyitó egyenleg </th>
   <th> Előírt közösköltség </th>
   <th> Befizetések </th>
   <th> Egyenleg </th>

     
</tr>
   
EOT;
    foreach ($deposit as $row) {
        echo '<tbody>';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value)) {
                if (($value > 999) || ($value < 0)) {
                    echo '<td style="text-align:right;">' . number_format($value, 0, ',', ' ') . '</td>';
                } else {
                    echo '<td style="text-align:right;">' . str_replace(".", ",", round($value, 2)) . '</td>';
                }
            } else {
                echo '<td>' . $value . '</td>';
            }
        }
        echo '</tr>';
//print_r($deposit);
    }
    echo '<tr>';
    echo '<td class="tdprimary" colspan=4>Összesen:</td>';
    echo "<td class='tdwarning' style='text-align:right;'>" . number_format($allobalance, 0, ',', ' ') . " Ft</td>";
    echo "<td class='tdwarning' style='text-align:right;'>" . number_format($allccost, 0, ',', ' ') . " Ft</td>";
    echo "<td class='tdwarning' style='text-align:right;'>" . number_format($allpayment, 0, ',', ' ') . " Ft</td>";
    echo "<td class='tdwarning' style='text-align:right;'>" . number_format($allbalance, 0, ',', ' ') . " Ft</td>";
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function getAllPayment($id, $year) {
    $nextyear = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
    $lastyear = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));

    $sql = "SELECT SUM(`amount`) as amount FROM `payment` WHERE (`account_date` between '$lastyear' AND '$nextyear') AND `deposit_id` = $id ";
    $result3 = mysql_query($sql);
    if (!$result3) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result3)) {
        $payment = $row;
    }
    if ($payment['amount'] != NULL) {
        $all_payment = $payment['amount'];
    } else {
        $all_payment = 0;
    }
    return $all_payment;
}

function getOneDepoAccount($id, $year) {
    global $house;
    $date = date("Y.m.d");
    $sql = "SELECT `id`, `floor`, `door`, "
            . "`resident_name` FROM `deposits` WHERE id=$id;";
    $result1 = mysql_query($sql);
    if (!$result1) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    if (mysql_num_rows($result1) == 0) {
        echo <<<EOT
        <div class="buttons btn-back">
	   <form action="stat.php">
	   <button type="submit" class="btn btn-success btn-icon value="Vissza" ><i class="fa fa-arrow-circle-left"></i>Vissza</button>
	   </form>
        </div>
        <div class="content">
        Sajnos nincs ilyen lakás.
        </div>           
EOT;
        exit();
    }
    while ($row = mysql_fetch_assoc($result1)) {
        $deposit = $row;
    }

    $sql = "SELECT * from deposit_balance where year='$year' and deposit_id=$id";
    $result2 = mysql_query($sql);
    if (!$result2) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    if (mysql_num_rows($result2) == 0) {
        echo <<<EOT
        <div class="buttons btn-back">
	   <form action="stat.php">
	   <button type="submit" class="btn btn-success btn-icon value="Vissza" ><i class="fa fa-arrow-circle-left"></i>Vissza</button>
	   </form>
        </div>
        <div class="content">
        Sajnos nincs $year. évre megjelníthető adat.
        </div>           
EOT;
        exit();
    }
    while ($row = mysql_fetch_assoc($result2)) {
        $balance = $row;
    }
//niytóegyenleg
    $deposit['opening_balance'] = $balance['opening_balance'];
//összes közöksköltség
    $deposit['ccost'] = getAllCcost($id, $year);
//összes egyéb költség
    $deposit['ocost'] = getAllOcost($id, $year);
//összes befizetés
    $deposit['payment'] = getAllPayment($id, $year);
//egyenleg
    $deposit['balance'] = ($deposit['opening_balance'] - (($deposit['ccost']) + $deposit['ocost'])) + $deposit['payment'];
//print_r($deposit);
    echo '<div class="content" id="section-to-print">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-list"></i> Éves kimutatás a(z) {$house['name']} {$deposit['floor']}/{$deposit['door']} lakására, $year. évre</h3>
<h4 class="primary"> Készült: $date </h4>

<div>
<a href="#" class="btn btn-primary btn-icon no-print" onclick="jQuery.print('#section-to-print')"><i class="fa fa-print"></i> Nyomtatás</a>
</div>    
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> id </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakó neve </th>
   <th> Nyitó egyenleg </th>
   <th> Előírt közösköltség </th>
   <th> További költségek </th>
   <th> Befizetések </th>
   <th> Egyenleg </th>

     
</tr>
   
EOT;
    echo '<td style="text-align:right;">' . $deposit['id'] . '</td>';
    echo '<td style="text-align:right;">' . $deposit['floor'] . '</td>';
    echo '<td style="text-align:right;">' . $deposit['door'] . '</td>';
    echo '<td style="text-align:right;">' . $deposit['resident_name'] . '</td>';
    echo '<td style="text-align:right;">' . number_format($deposit['opening_balance'], 0, ',', ' ') . '</td>';
    echo '<td style="text-align:right;">' . number_format($deposit['ccost'], 0, ',', ' ') . '</td>';
    echo '<td style="text-align:right;">' . number_format($deposit['ocost'], 0, ',', ' ') . '</td>';
    echo '<td style="text-align:right;">' . number_format($deposit['payment'], 0, ',', ' ') . '</td>';
    echo '<td style="text-align:right;">' . number_format($deposit['balance'], 0, ',', ' ') . '</td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '<hr />';
    getDepositPayments($year, $id);
    echo '<hr />';
    echo '<h3 class="primary"><i class="fa fa-history"></i> Az albetétre terhelt további költségek</h3>';
    getMyAllOcostCurrentYear($id);
    echo <<<EOT
    <hr/>
    <h4 class="primary"> Üzemeltetési bankszámlaszám: {$house['bank_account']} </h4>
    <p>Kérjük a befizetéseket a fenti bankszámlára teljesíteni!</p>
    <hr/>
    <p style="font-weight: bold;">Amennyiben naprakészen, online is szeretné követni közösköltsége alakulását, írjon egy üzenetet az {$house['infomail']} e-mail címre!</p>
    </div>
EOT;
}

function getAllPaymentTotal($year) {
    global $db;
    $date = date("Y.m.d");
    $nextyear = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
    $lastyear = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));

    $sql = "SELECT `payment`.`id`, `deposits`.`floor`,`deposits`.`door`,`deposits`.`resident_name`,`payment`.`account_date`,`payment`.`amount` FROM deposits "
            . "INNER JOIN `{$db['name']}`.`payment` ON `deposits`.`id` = `payment`.`deposit_id` WHERE (`account_date` between '$lastyear' AND '$nextyear') ORDER by `account_date` DESC ";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    if (mysql_num_rows($result) == 0) {
        echo <<<EOT
        <div class="content">
        Sajnos nincs $year. évre megjelníthető adat.
        </div>           
EOT;
        exit();
    }
    while ($row = mysql_fetch_assoc($result)) {
        $payment[] = $row;
    }
    echo '<div class="content" id="section-to-print">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-list"></i> $year évi befizetések listája</h3>
<h4 class="primary"> Készült: $date </h4>
<div>
<a href="#" class="btn btn-primary btn-icon no-print" onclick="jQuery.print('#section-to-print')"><i class="fa fa-print"></i> Nyomtatás</a>
</div>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> ID </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakó neve </th>
   <th> Befizetés dátuma </th>
   <th> Befizetett összeg </th>
   <th class="no-print"> Átkönyvel  </th>
  
</tr>
EOT;
    foreach ($payment as $row) {
        echo '<tbody>';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value)) {
                if (($value > 999) || ($value < 0)) {
                    echo '<td style="text-align:right;">' . number_format($value, 0, ',', ' ') . '</td>';
                } else {
                    echo '<td style="text-align:right;">' . str_replace(".", ",", round($value, 2)) . '</td>';
                }
            } else {
                echo '<td>' . $value . '</td>';
            }
        }
        echo "<td class='no-print'><a href='reaccount.php?id={$row['id']}&floor={$row['floor']}&door={$row['door']}&amount={$row['amount']}'>Átkönyvel</a></td>";
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
//print_r($deposit);
}

function getHousePayments($year) {
    global $db;
    global $house;
    $date = date("Y.m.d");
    $nextyear = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
    $lastyear = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));

    $sql = "SELECT `payment`.`id`, `deposits`.`resident_name`,`payment`.`account_date`,`payment`.`amount` FROM deposits "
            . "INNER JOIN `{$db['name']}`.`payment` ON `deposits`.`id` = `payment`.`deposit_id` WHERE (`account_date` between '$lastyear' AND '$nextyear') and `deposits`.`id`={$house['deposit_id']} ORDER by `account_date` DESC ";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    if (mysql_num_rows($result) == 0) {
        echo '<div class="content">';
        echo 'Nincs nem lekönyvelhető befizetés';
        echo '</div>';
        exit();
    } else {
        while ($row = mysql_fetch_assoc($result)) {
            $payment[] = $row;
        }
        echo <<<EOT
        <div class="buttons btn-back">
	   <form action="stat.php">
	   <button type="input" name="submit" class="btn btn-success value="Vissza" ><i class="fa fa-arrow-circle-left"></i> Vissza</button>
	   </form>    
<h3 class="primary"><i class="fa fa-list"></i> $year évi nem lekönyvelhető befizetések listája</h3>
<h4 class="primary"> Készült: $date </h4>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> ID </th>
   <th> Társasház </th>
   <th> Befizetés dátuma </th>
   <th> Befizetett összeg </th>
   <th> Átkönyvelés </th>
  
</tr>
EOT;
        foreach ($payment as $row) {
            echo '<tbody>';
            echo '<tr>';
            foreach ($row as $value) {
                if (is_numeric($value)) {
                    if (($value > 999) || ($value < 0)) {
                        echo '<td style="text-align:right;">' . number_format($value, 0, ',', ' ') . '</td>';
                    } else {
                        echo '<td style="text-align:right;">' . str_replace(".", ",", round($value, 2)) . '</td>';
                    }
                } else {
                    echo '<td>' . $value . '</td>';
                }
            }
            echo "<td><a href='reaccount.php?id={$row['id']}&floor=0&door=0&amount={$row['amount']}'>Átkönyvel</a></td>";
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
//print_r($deposit);
    }
}

function getPaymentData($id) {
    $sql = "SELECT `payment`.`id`, `payment`.`deposit_id`, `payment`.`amount`,`payment`.`account_date` FROM payment where `payment`.`id` = $id";
    $result = mysql_query($sql);
    if (!$result) {
        echo "hiba:" . mysql_errno() . ": " . mysql_error();
        exit;
    }
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    echo '<div class="content">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-list"></i> Befizetés adatai</h3>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> id </th>
   <th> lakás id </th>
   <th> Összeg </th>
   <th> Könyvelés dátuma </th>
   
</tr>

   
EOT;
    foreach ($table as $row) {
        echo '<tbody>';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value)) {
                echo '<td>' . number_format($value, 0, ',', ' ') . '</td>';
            } else {
                echo '<td>' . $value . '</td>';
            }
        }
        echo '</tr>';
        echo '</tbody>';
    }
    echo '</table>';
    echo '</div>';
}

function changeDepoOnPayment($data) {
    $sql = "UPDATE  `payment` SET  `deposit_id` =  '{$data['did']}' WHERE  `payment`.`id` ={$data['id']};";
    $res = mysql_query($sql);
    if (!$res) {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
    return $res;
}

function changeBalanceByReaccount($data) {
    global $db;
    $year = date('Y');
    $oldbalance_old = getCurrentBalance($data['oldid']); // régi lakás egyenlege
    $oldbalance_new = $oldbalance_old - $data['amount'];
    $newbalance_old = getCurrentBalance($data['did']); // új lakás egyenlege
    $newbalance_new = $newbalance_old + $data['amount'];
    $sql = "UPDATE `{$db['name']}`.`deposit_balance` SET `actual_balance` = '$oldbalance_new' "
            . "WHERE `deposit_balance`.`deposit_id` = {$data['oldid']} AND `year` = $year";
    $result1 = mysql_query($sql);
    if (!$result1) {
        die("updateCurrentBalance hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $sql = "UPDATE `{$db['name']}`.`deposit_balance` SET `actual_balance` = '$newbalance_new' "
            . "WHERE `deposit_balance`.`deposit_id` = {$data['did']} AND `year` = $year";
    $result2 = mysql_query($sql);
    if (!$result2) {
        die("updateCurrentBalance hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function getDepositPayments($year, $id) {
    global $db;
    $date = date("Y.m.d");
    $nextyear = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
    $lastyear = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));

    $sql = "SELECT `payment`.`id`, `deposits`.`floor`, `deposits`.`door`, `payment`.`account_date`,`payment`.`amount` FROM deposits "
            . "INNER JOIN `{$db['name']}`.`payment` ON `deposits`.`id` = `payment`.`deposit_id` WHERE (`account_date` between '$lastyear' AND '$nextyear') and `deposits`.`id`=$id ORDER by `account_date` DESC ";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    if (mysql_num_rows($result) == 0) {
        echo '<div class="content">';
        echo "Nincs könyvelt befizetés $year évre.";
        echo '</div>';
    } else {
        while ($row = mysql_fetch_assoc($result)) {
            $payment[] = $row;
        }
        echo <<<EOT

<h3 class="primary"><i class="fa fa-list"></i> $year évi befizetések listája</h3>
<h4 class="primary"> Készült: $date </h4>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> Tétel ID </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Befizetés dátuma </th>
   <th> Befizetett összeg </th>
   <th class="no-print"> Átkönyvelés </th>
  
</tr>
EOT;
        foreach ($payment as $row) {
            echo '<tbody>';
            echo '<tr>';
            foreach ($row as $value) {
                if (is_numeric($value)) {
                    if (($value > 999) || ($value < 0)) {
                        echo '<td style="text-align:right;">' . number_format($value, 0, ',', ' ') . '</td>';
                    } else {
                        echo '<td style="text-align:right;">' . str_replace(".", ",", round($value, 2)) . '</td>';
                    }
                } else {
                    echo '<td>' . $value . '</td>';
                }
            }
            echo "<td class='no-print'><a href='reaccount.php?id={$row['id']}&floor={$row['floor']}&door={$row['door']}&amount={$row['amount']}'>Átkönyvel</a></td>";
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
}

function getDepositCcost($id, $year) {
    global $db;
    $sql = "SELECT `ccost`.`id`, `deposits`.`floor`,`deposits`.`door`,`ccost`.`year`,`ccost`.`month`,`ccost`.`ccost` FROM deposits "
            . "LEFT JOIN `{$db['name']}`.`ccost` ON `deposits`.`id` = `ccost`.`deposit_id` where year = '$year' and `deposits`.`id`='$id'";
    $result = mysql_query($sql);
    if (!$result) {
        echo "getCcost hiba - " . mysql_errno() . ": " . mysql_error();
        exit;
    }
    if (mysql_num_rows($result) == 0) {
        echo '<div class="content">';
        echo "Nincs könyvelt közösköltség $year évre.";
        echo '</div>';
        exit();
    } else {
        while ($row = mysql_fetch_assoc($result)) {
            $ccost[] = $row;
        }
        echo <<<EOT
        <div class="buttons btn-back">
	   <form action="stat.php">
	   <button type="submit" class="btn btn-success value="Vissza" ><i class="fa fa-arrow-circle-left"></i> Vissza</button>
	   </form>    
<h3 class="primary"><i class="fa fa-list"></i> $year évi közösköltség alakulása</h3>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> Tétel ID </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Év </th>
   <th> Hónap </th>
   <th> Közösköltség </th>
   <th> Változtat </th>
</tr>
EOT;
        foreach ($ccost as $row) {
            echo '<tbody>';
            echo '<tr>';
            foreach ($row as $value) {
                if (is_numeric($value)) {
                    if (($value > 2099) || ($value < 0)) {
                        echo '<td style="text-align:right;">' . number_format($value, 0, ',', ' ') . '</td>';
                    } else {
                        echo '<td style="text-align:right;">' . str_replace(".", ",", round($value, 2)) . '</td>';
                    }
                } else {
                    echo '<td>' . $value . '</td>';
                }
            }
            echo "<td class='no-print'><a href='updateccost.php?id={$row['id']}'>Változtat</a></td>";
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
}

function getACcost($id) {
    global $db;
    $sql = "SELECT `ccost`.`id`,`deposits`.`floor`,`deposits`.`door`,`ccost`.`year`,`ccost`.`month`,`ccost`.`ccost` FROM ccost "
            . "LEFT JOIN `{$db['name']}`.`deposits` ON `ccost`.`deposit_id` = `deposits`.`id` where `ccost`.`id` = $id";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    echo '<div class="content">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-list"></i> Tétel adatai</h3>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> Tétel ID </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Év </th>
   <th> Hónap </th>
   <th> Közösköltség </th>
</tr>

   
EOT;
    foreach ($table as $row) {
        echo '<tbody>';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value)) {
                echo '<td>' . str_replace(".", ",", round($value, 2)) . '</td>';
            } else {
                echo '<td>' . $value . '</td>';
            }
        }
        echo '</tr>';
        echo '</tbody>';
    }
    echo '</table>';
    echo '</div>';
    return $row;
}

function updateCcostDb($data) {
    global $db;
    $sql = "UPDATE `{$db['name']}`.`ccost` SET `ccost` = {$data['ccost']} WHERE `ccost`.`id` = {$data['id']};";
    $result = mysql_query($sql);
    if (!$result) {
        die("update ccost hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function updateAllBalance($year) {
    global $db;
    $nextyear = date("Y-m-d", mktime(0, 0, 0, 12, 31, $year));
    $lastyear = date("Y-m-d", mktime(0, 0, 0, 1, 1, $year));
    $sql = "SELECT COUNT(`id`) as count FROM `deposits`";
    $result = mysql_query($sql);
    if (!$result) {
        echo "select deposit number hiba -" . mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $depo = $row['count'];
    }

    for ($i = 1; $i < $depo + 1; $i++) {
        $sql = "SELECT SUM(`amount`) as amount FROM `payment` WHERE `deposit_id`=$i AND (`account_date` between '$lastyear' AND '$nextyear')";

        $result = mysql_query($sql);
        if (!$result) {
            echo "select amount $i hiba -" . mysql_errno() . ": " . mysql_error();
            exit;
        }
        while ($row = mysql_fetch_assoc($result)) {
            $amount = $row['amount'];
        }
        $sql = "SELECT `opening_balance` FROM `deposit_balance` WHERE `deposit_id`=$i AND `year`=$year";
        $result = mysql_query($sql);
        if (!$result) {
            echo "select balance $i hiba -" . mysql_errno() . ": " . mysql_error();
            exit;
        }
        while ($row = mysql_fetch_assoc($result)) {
            $balance = $row['opening_balance'];
        }
        $actual = $balance + $amount;
        $sql = "UPDATE `{$db['name']}`.`deposit_balance` SET `actual_balance` = $actual WHERE `deposit_balance`.`deposit_id` = $i and `deposit_balance`.`year`=$year;";
        $result = mysql_query($sql);
        if (!$result) {
            echo "update balance $i hiba -" . mysql_errno() . ": " . mysql_error();
            exit;
        } else {
            echo "$i done<br>";
        }
    }
}

function getLatestpaymentAccDate() {
    $sql = "SELECT `account_date` FROM `payment` order by `id` DESC LIMIT 1";
    $result = mysql_query($sql);
    if (!$result) {
        echo "getPaymenthiba -" . mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $date = $row["account_date"];
    }
    $year = substr($date, 0, 4);
    return $year;
}

function insertOcost($data) {
    global $db;
    $year = date('Y');
    $oldbalance = getCurrentBalance($data['deposit_id']);
    $newbalance = $oldbalance - $data['ocost'];
    $sql = "INSERT INTO ocost (`year`, `month`, `day`, `deposit_id`, `ocost`, `title`) "
            . "VALUES ('{$data['year']}', '{$data['month']}', '{$data['day']}', '{$data['deposit_id']}', '{$data['ocost']}', '{$data['title']}')";
    echo $sql;
    $result = mysql_query($sql);
    if (!$result) {
        die("insertIntoOcost hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $sql = "UPDATE `{$db['name']}`.`deposit_balance` SET `actual_balance` = '$newbalance' "
            . "WHERE `deposit_balance`.`deposit_id` = {$data['deposit_id']} AND `year` = $year";
//echo $sql;
    $result = mysql_query($sql);
    if (!$result) {
        die("updateCurrentBalance hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function getMyAllOcost($id) {
    global $db;
    $sql = "SELECT `deposits`.`floor`,`deposits`.`door`,`ocost`.`year`,`ocost`.`month`,`ocost`.`day`,`ocost`.`ocost`, `ocost`.`title` FROM deposits "
            . "LEFT JOIN `{$db['name']}`.`ocost` ON `deposits`.`id` = `ocost`.`deposit_id` WHERE `deposits`.`id` = $id 
        ORDER BY `ocost`.`year` DESC, `ocost`.`month` DESC, `ocost`.`day` DESC";
    $result = mysql_query($sql);
    if (!$result) {
        die("geMyAllOcost hiba:" . mysql_errno() . " - " . mysql_error());
    }

    while ($row = mysql_fetch_assoc($result)) {
        $ocost[] = $row;
    }
    if ($ocost[0]['ocost'] == "") {
        echo "Nincsenek további költségek az albetétre terhelve.";
    } else {
        echo <<<EOT
        <table id="responsiveTable" class="large-only" cellspacing="0">
            <tr align="left" class="primary">
                <th> Emelet </th>
                <th> Ajtó </th>
                <th> Dátum </th>
                <th> Költség</th>
                <th> Jogcím</th>
            </tr>
            <tbody>
EOT;
        foreach ($ocost as $row) {
            echo '<tr>';
            
                if (strlen($row['month']) == 1) {
                    $row['month'] = "0" . $row['month'];
                }
                if (strlen($row['day']) == 1) {
                    $row['day'] = "0" . $row['day'];
                }
                echo '<tr>';
                echo "<td>{$row['floor']}</td>";
                echo "<td>{$row['door']}</td>";
                echo "<td>{$row['year']}.{$row['month']}.{$row['day']}</td>";
                echo "<td>" . number_format($row['ocost'], 0, ',', ' ') . " Ft</td>";
                echo "<td>{$row['title']}</td>";
                echo '</tr>';
           
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
}

function getMyAllOcostCurrentYear($id) {
    global $db;
    $year = date('Y');
    $sql = "SELECT `deposits`.`floor`,`deposits`.`door`,`ocost`.`year`,`ocost`.`month`,`ocost`.`day`,`ocost`.`ocost`, `ocost`.`title` FROM deposits "
            . "LEFT JOIN `{$db['name']}`.`ocost` ON `deposits`.`id` = `ocost`.`deposit_id` WHERE `deposits`.`id` = $id AND `ocost`.`year` = $year "
            . "ORDER BY `ocost`.`year` DESC, `ocost`.`month` DESC, `ocost`.`day` DESC";
    $result = mysql_query($sql);
    if (!$result) {
        die("geMyAllOcost hiba:" . mysql_errno() . " - " . mysql_error());
    }

    while ($row = mysql_fetch_assoc($result)) {
        $ocost[] = $row;
    }

    if (mysql_num_rows($result) == 0) {
        echo "Nincsenek további költségek az albetétre terhelve.";
    } else {
        echo <<<EOT
        <table id="responsiveTable" class="large-only" cellspacing="0">
            <tr align="left" class="primary">
                <th> Emelet </th>
                <th> Ajtó </th>
                <th> Dátum </th>
                <th> Költség</th>
                <th> Jogcím</th>
            </tr>
            <tbody>
EOT;
        foreach ($ocost as $row) {
            if (strlen($row['month']) == 1) {
                $row['month'] = "0" . $row['month'];
            }
            if (strlen($row['day']) == 1) {
                $row['day'] = "0" . $row['day'];
            }
            echo '<tr>';
            echo "<td>{$row['floor']}</td>";
            echo "<td>{$row['door']}</td>";
            echo "<td>{$row['year']}.{$row['month']}.{$row['day']}</td>";
            echo "<td>" . number_format($row['ocost'], 0, ',', ' ') . " Ft</td>";
            echo "<td>{$row['title']}</td>";
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
}

function getAllOcost($id, $year) {
    $sql = "SELECT ocost from ocost WHERE `deposit_id`=$id and `year`=$year ";
//echo $sql;
    $result = mysql_query($sql);
    if (!$result) {
        die("getOllCcost hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $ccost = array();
    while ($row = mysql_fetch_assoc($result)) {
        $ocost[] = $row;
    }
//print_r($ccost);
    $all_ocost = 0;
    for ($i = 0; ($i < mysql_num_rows($result)); $i++) {
        $all_ocost += $ocost[$i]['ocost'];
    }
    return $all_ocost;
}

function updateUserData($userdata) {
    global $db;
    $sql = "UPDATE `{$db['name']}`.`residents` SET `firstname` = '{$userdata['firstname']}', "
            . "`lastname` = '{$userdata['lastname']}',`email` = '{$userdata['email']}',"
            . "`phone` = '{$userdata['phone']}' "
            . "WHERE `residents`.`id` = {$userdata['id']};";
//echo $sql;
    $res = mysql_query($sql);
    if (!$res) {
        echo "update user hiba -" . mysql_errno() . ": " . mysql_error();
        exit();
    }
}

function updateLastLogin($id) {
    global $db;
    $sql = $sql = "UPDATE `{$db['name']}`.`residents` "
            . "SET `last_login` = NOW() WHERE `residents`.`id` = $id;";
//echo $sql;
    $res = mysql_query($sql);
    if (!$res) {
        echo "update login hiba -" . mysql_errno() . ": " . mysql_error();
        exit();
    }
}
