<?php

include_once 'functions.php';

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

function listDeposits() {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = $sql = "SELECT `id`, `floor`, `door`, `area`, `garage_area`, "
            . "(`area`+`garage_area`), `residents_no`, `area_ratio`, "
            . "`garage_area_ratio`, (`area_ratio`+`garage_area_ratio`), "
            . "`watermeter`, `resident_name` FROM `deposits`;";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    echo <<<EOT
'<div class="content">
<h3 class="success">Izeke</h3>
<table id="responsiveTable" class="large-only" cellspacing="0">'
<tr align="left" class="warning">
   <th> id </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakás terület (nm) </th>
   <th> Garázs terület (nm) </th>
   <th> Össz terület (nm) </th>
   <th> Lakók száma </th>
   <th> Lakás tulajdoni hányad </th>
   <th> Garázs tulajdoni hányad </th>
   <th> Összes tulajdoni hányad </th>
   <th> Vízóra </th>
   <th> Lakó neve </th>
   <th> Részletek </th>
   <th> Módosítás </th>
</tr>
   
EOT;
    foreach ($table as $row) {
        echo '<tbody class="table-hover">';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value)) {
                echo '<td>' . str_replace(".", ",", round($value, 2)) . '</td>';
            } else {
                echo '<td>' . $value . '</td>';
            }
        }
        echo "<td><a href=\"mydepo.php?depositid=" . $row['id'] . "\">Részletek</a></td>";
        echo "<td><a href=\"updatedeposit.php?id=" . $row['id'] . "\" target=\"blank\">Módosít</a></td>";
        echo '</tr>';
        echo '</tbody>';
    }
    echo '</table>';
    echo '</div>';
}

function insertDepoDb($deposit, $con) {
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
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$ta' where `multiplier` = '/terület-egység';";
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
        $db = $row['db'];
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$db' where `multiplier` = '/fő';";
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

    $sql = "UPDATE `plainhouse`.`deposits` SET `floor` = '{$deposit['floor']}', "
            . "`door` = '{$deposit['door']}',`area` = '{$deposit['area']}',"
            . "`residents_no` = '{$deposit['residents']}',"
            . "`area_ratio`= '{$deposit['area_ratio']}', "
            . "`resident_name` = '{$deposit['resident_name']}' "
            . "WHERE `deposits`.`id` = {$deposit['id']};";
    //echo $sql;
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo mysql_errno() . ": " . mysql_error();
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
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$ta' where `multiplier` = '/terület-egység';";
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
        $db = $row['db'];
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$db' where `multiplier` = '/fő';";
    $res = mysql_query($sql, $con);
    if (!$res) {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
}

function getMyDepo($id) {
	global $user;
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
echo <<<EOT
			<div class="content"><div class="row">
		<div class="col-md-8">
			<h3 class="primary">Személyes adatok</h3>


			<p class="lead">
				<img alt="Tenant Avatar" src="pics/user_avatar.png" class="avatar" />
				{$user['firstname']} {$user['lastname']}<br />
				{$user['email']}<br />
				{$user['phone']}</p>
			<p class="lead">{$user['cim']}</p>
		</div>

		<div class="col-md-4">
	        <div class="list-group">
				<li class="list-group-item active">Motorház</li>
				<a data-toggle="modal" href="#ktgAlakul" class="list-group-item">Közösköltség alakulása</a>
				<a data-toggle="modal" href="#newMsg" class="list-group-item">Üzenet küldése</a>
				<a data-toggle="modal" href="#editPassword" class="list-group-item">Jelszó módosítása</a>
	        </div>
		</div>
	</div>

EOT;
    getMyAllCcost($id);
    sendMessage();
    echo '<span style="text-align:right;">';
    changePassword($_SESSION['userid']);
    echo '</span>';
    echo '</div>';
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

    echo '<div class="content">';
    echo <<<EOT
<h3 class="primary"><i class="fa fa-list"></i> Albetétek - lista</h3>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> id </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakás terület (nm) </th>
   <th> Lakók száma </th>
   <th class="tool-tip" title="Lakás tulajdoni hányad"> Lakás th </th>
   <th> Lakó neve </th>
   <th> Közösktg. </th>
   <th> Egyenleg </th>
   <th> Részletek </th>
   <th> Befizetés </th>
   <th> Módosítás </th>
     
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
        echo "<td><a href=\"mydepo.php?depositid=" . $row['id'] . "\">Részletek</a></td>";
        echo "<td><a href=\"payment.php?id=" . $row['id'] . "\">Új befizetés</a></td>";
        echo "<td><a href=\"updatedeposit.php?id=" . $row['id'] . "\" target=\"blank\">Módosít</a></td>";
        echo '</tr>';
    }
    echo '<tr>';
    echo '<td class="tdprimary" colspan=3>Összesen:</td>';
    echo "<td class='tdwarning' style='text-align:right;'>" . number_format($sumarea, 0, ',', ' ') . " m<sup>2</sup></td>";
    echo "<td class='tdwarning' style='text-align:right;'>$sumresidents fő</td>";
    echo "<td class='tdwarning' style='text-align:right;'>" . number_format(str_replace(".", ",", round($sumarearatio, 2)), 0, ',', ' ') . "</td>";
    echo '<td class="tdprimary"></td>';
    echo "<td class='tdwarning' style='text-align:right;'>" . number_format($sumccost, 0, ',', ' ') . "</td>";
    echo "<td class='tdwarning' style='text-align:right;'>" . number_format($sumbalance, 0, ',', ' ') . "</td>";
    echo '<td class="tdprimary" colspan=3></td>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function listResidents() {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT `residents`.`id`,`residents`.`firstname`,`residents`.`lastname`,"
            . "`residents`.`email`,`residents`.`username`,`deposits`.`floor`,"
            . "`deposits`.`door`,`residents`.`active`,`residents`.`admin` "
            . "FROM residents LEFT JOIN `deposits` ON `residents`.`depositid` = `deposits`.`id` "
            . "order by `deposits`.`floor`, `deposits`.`door`";

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
            if ($table[$i]['admin'] < 1) {
                $table[$i]['admin'] = "nem admin";
            } else {
                $table[$i]['admin'] = "admin";
            }
        }
        echo '<div class="content">';
        echo '<h3 class="primary"><i class="fa fa-users"></i> Regisztrált lakók </h3>';
        echo '<table id="responsiveTable" class="large-only" cellspacing="0">';
        echo <<<EOT
   <tr align="left" class="primary">
   <th> ID </th>
   <th> Vezetéknév </th>
   <th> Kresztnév </th>
   <th> e-mail cím</th>
   <th> Felhasználónév</th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Aktív </th> 
   <th> Admin </th> 
   <th> Státusz módosítása  </th>
   <th> Admin rang kiosztása  </th>
   <th> Jelszó módosítás </th>
   <th> Törlés</th>
   </tr>
EOT;
        echo "<tbody>";
        foreach ($table as $row) {

            echo '<tr>';
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            if ($row['active'] == "aktív") {
                echo "<td><a id=\"alink\" href=\"update_ustatus.php?uid={$row['id']}"
                . "&status=1\">User letiltása</a></td>";
            } else {
                echo "<td><a id=\"alink\" href=\"update_ustatus.php?uid={$row['id']}"
                . "&status=0\">User aktiválása</a></td>";
            }
            if ($row['admin'] == "admin") {
                echo "<td><a id=\"alink\" href=\"update_astatus.php?uid={$row['id']}"
                . "&status=1\">Admin jog megvonása</a></td>";
            } else {
                echo "<td><a id=\"alink\" href=\"update_astatus.php?uid={$row['id']}"
                . "&status=0\">Admin jog kiosztása</a></td>";
            }

            echo <<<EOT
        <td><a href="update_upassword.php?uid={$row['id']}">Jelszó módosítás</a></td>
        <td><a href="killuser.php?uid={$row['id']}">Lakó törlése</a></td>
EOT;
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
            . "`residents`.`email`,`residents`.`username`,`deposits`.`floor`,"
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
                echo "<td>$value Ft</td>";
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

    $sql = "UPDATE `plainhouse`.`fees` SET `yearly_amount` = {$data['ccost']} WHERE `fees`.`id` = 1;";
    $result = mysql_query($sql);
    if (!$result) {
        die("update ccost hiba:" . mysql_errno() . " - " . mysql_error());
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `yearly_amount` = {$data['ccost']} WHERE `fees`.`id` = 1;";
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
        echo "Hiba! Nincs ilyen albetét az aktuális évben!";
        exit;
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
            . "ORDER BY `payment`.`date` DESC;";
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
        . "Felhívjuk figyelmét, hogy a befizetések azok beérkezése után 3-5 nappal kerülnek könyvelésre!";
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
        echo '<p>Felhívjuk figyelmét, hogy a befizetések azok beérkezése után 3-5 nappal kerülnek könyvelésre!</p>';
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

function getMyAllCcost($id) {
    $sql = "SELECT `deposits`.`floor`,`deposits`.`door`,`ccost`.`year`,`ccost`.`month`,`ccost`.`ccost` FROM deposits "
            . "LEFT JOIN `plainhouse`.`ccost` ON `deposits`.`id` = `ccost`.`deposit_id` WHERE `deposits`.`id` = $id 
        ORDER BY `ccost`.`year` DESC, `ccost`.`month` DESC LIMIT 12";
    $result = mysql_query($sql);
    if (!$result) {
        die("geMyAllCcost hiba:" . mysql_errno() . " - " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($result)) {
        $ccost[] = $row;
    }
    echo '<button id="ccostabutton" value="Befizetesek" class="btn btn-success btn-icon"><i class="fa fa-money"></i>Közösköltség alakulása </button>';
        echo '<div id=ccosts>';
    echo '<h3 class="primary"><i class="fa fa-money"></i> Közösköltség alakulása az utolsó 12 hónapban</h3>';
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
    echo '</div>';
    echo '<hr/>';
}
