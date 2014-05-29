<?php

function connectDb() {
    global $db;
    $con = mysql_connect($db['host'], $db['user'], $db['pass']);

    if (!$con)
    {
        die('Nem tudok kapcsolódni: ' . mysql_error());
    }
    mysql_select_db($db['name'], $con);
    mysql_set_charset($db['charset'], $con);
    if (!mysql_select_db($db['name'], $con))
    {
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
    if (!$res)
    {
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
    if (!$result)
    {
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
            if (is_numeric($value))
            {
                echo '<td>' . str_replace(".", ",", round($value, 2)) . '</td>';
            }
            else
            {
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
    if (!$res)
    {
        echo mysql_errno() . "(insert): " . mysql_error();
        exit();
    }
    $sql = "SELECT SUM(`deposits`.`area`)as t_a FROM `deposits`;";
    $result = mysql_query($sql);
    if (!$result)
    {
        echo mysql_errno() . "(ta): " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $ta = $row['t_a'];
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$ta' where `multiplier` = '/terület';";
    $res = mysql_query($sql, $con);
    if (!$res)
    {
        echo mysql_errno() . "(db): " . mysql_error();
        exit();
    }
    $sql = "SELECT SUM(`residents_no`) as db from deposits;";
    $result = mysql_query($sql);
    if (!$result)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $db = $row['db'];
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$db' where `multiplier` = '/fő';";
    $res = mysql_query($sql, $con);
    if (!$res)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
}

function getDepositId($floor, $door) {
    $sql = "select id from deposits where floor=\"$floor\" and door=\"$door\";";
    $res = mysql_query($sql);
    if (!$res)
    {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0)
    {
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
    if (!$res)
    {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit();
    }

    if (mysql_num_rows($res) == 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}

function getUserRole($userdata) {
    $sql = "select admin from residents where username=\"{$userdata['user']}\";";
    $res = mysql_query($sql);
    if (!$res)
    {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0)
    {
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
    if (!$res)
    {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0)
    {
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
    if (!$res)
    {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0)
    {
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
    if (!$res)
    {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0)
    {
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
    if (!$res)
    {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit();
    }

    if (mysql_num_rows($res) == 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}

function getAdminRole($userdata) {
    $sql = "select role from admin where username=\"{$userdata['user']}\";";
    $res = mysql_query($sql);
    if (!$res)
    {
        echo "A ($sql) kérdés futtatása sikertelen: " . mysql_error();
        exit;
    }

    if (mysql_num_rows($res) == 0)
    {
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
    if (!$result)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    echo '<div class="content"><table id="results">';
    echo <<<EOT
<thead>
<tr>
   <th> id </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakás terület (nm) </th>
   <th> Lakók száma </th>
   <th> Lakás tulajdoni hányad </th>
   <th> Lakó neve </th>

</tr>
</thead>
   
EOT;
    foreach ($table as $row) {
        echo '<tbody>';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value))
            {
                echo '<td>' . str_replace(".", ",", round($value, 2)) . '</td>';
            }
            else
            {
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
    if (!$res)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
    $sql = "SELECT SUM(`residents_no`) as db from deposits;";
    $result = mysql_query($sql);
    if (!$result)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $db = $row['db'];
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$db' where `multiplier` = '/fő';";
    $res = mysql_query($sql, $con);
    if (!$res)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
}

function getMyDepo($id) {
    $sql = "SELECT `floor`, `door`, `area`,  "
            . "`residents_no`, `area_ratio`, "
            . "`resident_name` FROM `deposits` WHERE `id`=$id;";
    $result1 = mysql_query($sql);
    if (!$result1)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $deposit = array();
    while ($row = mysql_fetch_assoc($result1)) {
        $deposit = $row;
    }
    $sql = "SELECT * from fees;";
    $result2 = mysql_query($sql);
    if (!$result2)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $fees = array();
    while ($row = mysql_fetch_assoc($result2)) {
        $fees[] = $row;
    }
    $ccost = $fees[0];
    $grabage = $fees[1];
    
    $sumarea = $sumresidents = $sumarearatio = $sumccost = 0;
          
        $ccost_cost = round(($ccost['yearly_amount'] * $deposit['area']), 0);
        $grabage_cost = round(($grabage['yearly_amount'] * $deposit['residents_no']), 0);

    $ccosts = $ccost_cost + $grabage_cost;


    echo <<<EOT
<div class="content">
<h3 class="primary"> Albetét adatai </h3>
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
<hr />
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
   <td>Megoszt. módja</td>
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
    echo "<td>Közösktg.</td>";
    echo "<td>" . number_format($ccost_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($grabage_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td class='tdwarning'>" . number_format($ccosts, 0, ',', ' ') . " Ft/hó</td>";
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function getAllDepo() {
    
    $sql = "SELECT `id`, `floor`, `door`, `area`, "
            . "`residents_no`, `area_ratio`, "
            . "`resident_name` FROM `deposits`;";
    $result1 = mysql_query($sql);
    if (!$result1)
    {
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
    if (!$result2)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $fees = array();
    while ($row = mysql_fetch_assoc($result2)) {
        $fees[] = $row;
    }
    $ccost = $fees[0];
    $grabage = $fees[1];
    

    $sumarea = $sumresidents = $sumarearatio = $sumccost = 0;
    for ($i = 0; $i < $deprows; $i++) {
        
        $ccost_cost = round(($ccost['yearly_amount'] * $deposit[$i]['area']), 0);
        $grabage_cost = round(($grabage['yearly_amount'] * $deposit[$i]['residents_no']), 0);
        

        $deposit[$i]["ccost"] = $ccost_cost + $grabage_cost;
        $sumarea += $deposit[$i]['area'];
        $sumresidents += $deposit[$i]['residents_no'];
        $sumarearatio += $deposit[$i]['area_ratio'];
        $sumccost +=$deposit[$i]["ccost"];
    }

    echo '<div class="content">';
    echo <<<EOT
<h3 class="primary">Albetétek - lista</h3>
<table id="responsiveTable" class="large-only" cellspacing="0">
<tr align="left" class="primary">
   <th> id </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Lakás terület (nm) </th>
   <th> Lakók száma </th>
   <th> Lakás tulajdoni hányad </th>
   <th> Lakó neve </th>
   <th> Közösktg. </th>
   <th> Részletek </th>
   <th> Módosítás </th>
</tr>
   
EOT;
    foreach ($deposit as $row) {
        echo '<tbody">';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value))
            {
                if ($value > 999)
                {
                    echo '<td>' . number_format($value, 0, ',', ' ') . '</td>';
                }
                else
                {
                    echo '<td>' . str_replace(".", ",", round($value, 2)) . '</td>';
                }
            }
            else
            {
                echo '<td>' . $value . '</td>';
            }
        }
        echo "<td><a href=\"mydepo.php?depositid=" . $row['id'] . "\">Részletek</a></td>";
        echo "<td><a href=\"updatedeposit.php?id=" . $row['id'] . "\" target=\"blank\">Módosít</a></td>";
        echo '</tr>';
    }
    echo '<tr>';
    echo '<td colspan=3>Összesen:</td>';
    echo "<td>" . number_format($sumarea, 0, ',', ' ') . " m<sup>2</sup></td>";
    echo "<td>$sumresidents fő</td>";
    echo "<td>" . number_format(str_replace(".", ",", round($sumarearatio, 2)), 0, ',', ' ') . "</td>";
    echo '<td></td>';
    echo "<td>" . number_format($sumccost, 0, ',', ' ') . "</td>";
    echo '<td colspan=2></td>';
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
            . "FROM residents LEFT JOIN `deposits` ON `residents`.`depositid` = `deposits`.`id`";

    $result = mysql_query($sql);
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    for ($i = 0; ($i < mysql_num_rows($result)); $i++) {
        if ($table[$i]['active'] == 1)
        {
            $table[$i]['active'] = "aktív";
        }
        else
        {
            $table[$i]['active'] = "nem aktív";
        }
        if ($table[$i]['admin'] < 1)
        {
            $table[$i]['admin'] = "nem admin";
        }
        else
        {
            $table[$i]['admin'] = "admin";
        }
    }
    echo '<div class="content">';
    echo  '<h3 class="primary"><i class="fa fa-users"></i> Regisztrált lakók </h3>';
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
   </tr>
EOT;
    echo "<tbody>";
    foreach ($table as $row) {
        
        echo '<tr>';
        foreach ($row as $value) {
        echo "<td>$value</td>";   
        }
        if ($row['active'] == "aktív")
        {
            echo "<td><a id=\"alink\" href=\"update_ustatus.php?uid={$row['id']}"
            . "&status=1\">User letiltása</a></td>";
        }
        else
        {
            echo "<td><a id=\"alink\" href=\"update_ustatus.php?uid={$row['id']}"
            . "&status=0\">User aktiválása</a></td>";
        }
        if ($row['admin'] == "admin")
        {
            echo "<td><a id=\"alink\" href=\"update_astatus.php?uid={$row['id']}"
            . "&status=1\">Admin jog megvonása</a></td>";
        }
        else
        {
            echo "<td><a id=\"alink\" href=\"update_astatus.php?uid={$row['id']}"
            . "&status=0\">Admin jog kiosztása</a></td>";
        }

 echo <<<EOT
        <td><a href="update_upassword.php?uid={$row['id']}">Jelszó módosítás</a></td>
EOT;
	        echo '</tr>';
	    }

	    echo '</tbody>';
	    echo '</table>';
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
    if (!$res)
    {
        die("Hiba:" . mysql_errno() . " - " . mysql_error());
    }
}

function changeUserPassword($id, $password) {
    $sql = "UPDATE  `residents` SET  `password` =  '$password' WHERE  `residents`.`id` =$id;";
    $res = mysql_query($sql);
    if (!$res)
    {
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
    if (!$res)
    {
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
    $array = array();
    while ($row = mysql_fetch_assoc($result)) {
        $array = $row;
    }
    return $array;
}


