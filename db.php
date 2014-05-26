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
    echo '<div class="content"><table id="results">';
    echo <<<EOT
<thead>
<tr>
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
        echo "<td><a href=\"mydepo.php?depositid=" . $row['id'] . "\">Részletek</a></td>";
        echo "<td><a href=\"updatedeposit.php?id=" . $row['id'] . "\" target=\"blank\">Módosít</a></td>";
        echo '</tr>';
        echo '</tbody>';
    }
    echo '</table>';
    echo '</div>';
}

function insertDepoDb($deposit, $con) {
    $sql = "INSERT INTO  deposits (floor ,door ,area, garage_area, residents_no, "
            . "area_ratio, garage_area_ratio, watermeter, resident_name) "
            . "values (\"{$deposit['floor']}\", \"{$deposit['door']}\", "
            . "\"{$deposit['area']}\", \"{$deposit['garage_area']}\", "
            . "\"{$deposit['residents']}\", \"{$deposit['area_ratio']}\", "
            . "\"{$deposit['garage_area_ratio']}\", \"{$deposit['watermeter']}\", "
            . "\"{$deposit['resident_name']}\")";
    //echo $sql;
    $res = mysql_query($sql, $con);
    if (!$res)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
    $sql = "SELECT (SUM(`area`)) as t_a from deposits";
    $result = mysql_query($sql);
    if (!$result)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $ta = $row['t_a'];
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$ta' where `multiplier` = '/terület';";
    $res = mysql_query($sql, $con);
    if (!$res)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
    $sql = "SELECT COUNT(`id`) as db from deposits;";
    $result = mysql_query($sql);
    if (!$result)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $db = $row['db'];
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$db' where `multiplier` = '/albetét';";
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
    $sql = "SELECT `id`, `floor`, `door`, `area`, `garage_area`, "
            . "(`area`+`garage_area`), `residents_no`, `area_ratio`, "
            . "`garage_area_ratio`, (`area_ratio`+`garage_area_ratio`), "
            . "`watermeter`, `resident_name` FROM `deposits` WHERE `id`=$id;";
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
   <th> Garázs terület (nm) </th>
   <th> Össz terület (nm) </th>
   <th> Lakók száma </th>
   <th> Lakás tulajdoni hányad </th>
   <th> Garázs tulajdoni hányad </th>
   <th> Összes tulajdoni hányad </th>
   <th> Vízóra </th>
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
            . "`garage_area` = '{$deposit['garage_area']}', `residents_no` = '{$deposit['residents']}',"
            . "`area_ratio`= '{$deposit['area_ratio']}', "
            . "`garage_area_ratio`= '{$deposit['garage_area_ratio']}', "
            . "`watermeter`= '{$deposit['watermeter']}', "
            . "`resident_name` = '{$deposit['resident_name']}' "
            . "WHERE `deposits`.`id` = {$deposit['id']};";
    //echo $sql;
    $res = mysql_query($sql, $con);
    if (!$res)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
    $sql = "SELECT (SUM(`area`)) as t_a from deposits";
    $result = mysql_query($sql);
    if (!$result)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    while ($row = mysql_fetch_assoc($result)) {
        $ta = $row['t_a'];
    }
    $sql = "UPDATE `plainhouse`.`fees` SET `dealer` = '$ta' where `multiplier` = '/terület';";
    $res = mysql_query($sql, $con);
    if (!$res)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
}

function getMyDepo($id) {
    $water_cost = $twater_cost = $junk_cost = $electrycity_cost = $gas_cost = $cam_cost = $costs_cost = $lift_cost = $m_water_cost = 0;
    $watermeter = "van";
    $sql = "SELECT `floor`, `door`, `area`, `garage_area`, "
            . "(`area`+`garage_area`) as areasum, `residents_no`, `area_ratio`, "
            . "`garage_area_ratio`, (`area_ratio`+`garage_area_ratio`) as ratiosum, "
            . "`watermeter`, `resident_name` FROM `deposits` WHERE `id`=$id;";
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
    $water = $fees[0];
    $twater = $fees[1];
    $junk = $fees[2];
    $electrycity = $fees[3];
    $gas = $fees[4];
    $cam = $fees[5];
    $costs = $fees[6];
    $lift = $fees[7];
    $insurance = $fees[8];
    $bank = $fees[9];
    $maintenance = $fees[10];
    $chimney = $fees[11];
    $cleaning = $fees[12];
    $renovation = $fees[13];
    $handling = $fees[14];
    if ($deposit['watermeter'] == 0)
    {
        $water_cost = round((($water['yearly_amount'] / 12) / $water['dealer']) * $deposit['residents_no'], 0);
        $m_water_cost = round((($water['yearly_amount'] / 12) / $water['dealer']), 0);
        $watermeter = "nincs";
    }
    $twater_cost = round((($twater['yearly_amount'] / 12) / $twater['dealer']), 0);
    $junk_cost = round(((($junk['yearly_amount'] / 12) / $junk['dealer']) * $deposit['ratiosum']), 0);
    $m_junk_cost = round(((($junk['yearly_amount'] / 12) / $junk['dealer'])), 0);
    $electrycity_cost = round(((($electrycity['yearly_amount'] / 12) / $electrycity['dealer']) * $deposit['ratiosum']), 0);
    $m_electrycity_cost = round(((($electrycity['yearly_amount'] / 12) / $electrycity['dealer'])), 0);
    $gas_cost = round(((($gas['yearly_amount'] / 12) / $gas['dealer']) * $deposit['area']), 0);
    $m_gas_cost = round(((($gas['yearly_amount'] / 12) / $gas['dealer'])), 0);
    $cam_cost = round(((($cam['yearly_amount'] / 12) / $cam['dealer']) * $deposit['ratiosum']), 0);
    $m_cam_cost = round(((($cam['yearly_amount'] / 12) / $cam['dealer'])), 0);
    $costs_cost = round(((($costs['yearly_amount'] / 12) / $costs['dealer']) * $deposit['ratiosum']), 0);
    $m_costs_cost = round(((($costs['yearly_amount'] / 12) / $costs['dealer'])), 0);
    $lift_cost = round(((($lift['yearly_amount'] / 12) / $lift['dealer']) * $deposit['ratiosum']), 0);
    $m_lift_cost = round(((($lift['yearly_amount'] / 12) / $lift['dealer'])), 0);
    $insurance_cost = round(((($insurance['yearly_amount'] / 12) / $insurance['dealer'])), 0);
    $bank_cost = round(((($bank['yearly_amount'] / 12) / $bank['dealer'])), 0);
    $maintenance_cost = round(((($maintenance['yearly_amount'] / 12) / $maintenance['dealer'])), 0);
    $chimney_cost = round(((($chimney['yearly_amount'] / 12) / $chimney['dealer'])), 0);
    $cleaning_cost = round(((($cleaning['yearly_amount'] / 12) / $cleaning['dealer'])), 0);
    $renovation_cost = round(((($renovation['yearly_amount'] / 12) / $renovation['dealer'])), 0);
    $handling_cost = round(((($handling['yearly_amount'] / 12) / $handling['dealer'])), 0);


    $ccosts = $water_cost + $twater_cost + $junk_cost + $electrycity_cost + $gas_cost +
                $cam_cost + $costs_cost + $lift_cost + $insurance_cost + $bank_cost + $maintenance_cost +
                $chimney_cost + $cleaning_cost + $renovation_cost + $handling_cost;


    echo <<<EOT
<div class="content">
<h3> Albetét adatai </h3>
<table id="results">
<thead>
<tr>
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
   <th>Havi közösköltség</th>
   
</tr>
</thead>
   
EOT;
    echo '<tbody>';
    echo '<tr>';
    echo "<td>{$deposit['floor']}</td>";
    echo "<td>{$deposit['door']}</td>";
    echo '<td>' . str_replace(".", ",", round($deposit['area'], 2)) . ' m<sup>2</sup></td>';
    echo '<td>' . str_replace(".", ",", round($deposit['garage_area'], 2)) . ' m<sup>2</sup></td>';
    echo '<td>' . str_replace(".", ",", round($deposit['areasum'], 2)) . ' m<sup>2</sup></td>';
    echo '<td>' . $deposit['residents_no'] . ' fő</td>';
    echo '<td>' . str_replace(".", ",", round($deposit['area_ratio'], 2)) . '</td>';
    echo '<td>' . str_replace(".", ",", round($deposit['garage_area_ratio'], 2)) . '</td>';
    echo '<td>' . str_replace(".", ",", round($deposit['ratiosum'], 2)) . '</td>';
    echo '<td>' . $watermeter . '</td>';
    echo '<td>' . $deposit['resident_name'] . '</td>';
    echo "<th>" . number_format($ccosts, 0, ',', ' ') . " Ft/hó</th>";
    echo '</tr>';
    echo '</tbody>';

    echo '</table>';

    echo <<<EOT
<hr>
   <p> </p>
<h3> Közösköltség részletezése </h3>
<table id="results">
<thead>
<tr>
   <th>Költségek</th>
   <th> {$water['name']} </th>
   <th> {$twater['name']}  </th>
   <th> {$junk['name']}  </th>
   <th> {$electrycity['name']} </th>
   <th> {$gas['name']} </th>
   <th> {$cam['name']} </th>
   <th> {$costs['name']} </th>
   <th> {$lift['name']} </th>
   <th> {$insurance['name']} </th>
   <th> {$bank['name']} </th>
   <th> {$maintenance['name']} </th>
   <th> {$chimney['name']} </th>
   <th> {$cleaning['name']} </th>
   <th> {$renovation['name']} </th>
   <th> {$handling['name']} </th>
   <th>Összesen</th>
</tr>
</thead>
<tr>
   <th>Megosztás módja</th>
    <td> {$water['multiplier']} </td>
   <td> {$twater['multiplier']} </td>
   <td> {$junk['multiplier']}  </td>
   <td> {$electrycity['multiplier']} </td>
   <td> {$gas['multiplier']} </td>
   <td> {$cam['multiplier']} </td>
   <td> {$costs['multiplier']} </td>
   <td> {$lift['multiplier']} </td>
   <td> {$insurance['multiplier']} </td>
   <td> {$bank['multiplier']} </td>
   <td> {$maintenance['multiplier']} </td>
   <td> {$chimney['multiplier']} </td>
   <td> {$cleaning['multiplier']} </td>
   <td> {$renovation['multiplier']} </td>
   <td> {$handling['multiplier']} </td>
   <th> </th>
</tr>
EOT;
    echo "<tr>";
    echo "<th>Éves díj</th>";
    echo "<td>" . number_format($water['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($twater['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($junk['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($electrycity['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($gas['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($cam['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($costs['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($lift['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($insurance['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($bank['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($maintenance['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($chimney['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($cleaning['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($renovation['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<td>" . number_format($handling['yearly_amount'], 0, ',', ' ') . " Ft/év </td>";
    echo "<th> </th>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Egységár</th>";
    echo "<td>" . number_format($m_water_cost, 0, ',', ' ') . " Ft/hó/fő</td>";
    echo "<td>" . number_format($twater_cost, 0, ',', ' ') . " Ft/hó/albetét</td>";
    echo "<td>" . number_format($m_junk_cost, 0, ',', ' ') . " Ft/hó/th</td>";
    echo "<td>" . number_format($m_electrycity_cost, 0, ',', ' ') . " Ft/hó/th</td>";
    echo "<td>" . number_format($m_gas_cost, 0, ',', ' ') . " Ft/hó/m<sup>2</sup></td>";
    echo "<td>" . number_format($m_cam_cost, 0, ',', ' ') . " Ft/hó/th</td>";
    echo "<td>" . number_format($m_costs_cost, 0, ',', ' ') . " Ft/hó/th</td>";
    echo "<td>" . number_format($m_lift_cost, 0, ',', ' ') . " Ft/hó/th</td>";
    echo "<td>" . number_format($insurance_cost, 0, ',', ' ') . " Ft/hó/albetét </td>";
    echo "<td>" . number_format($bank_cost, 0, ',', ' ') . " Ft/hó/albetét </td>";
    echo "<td>" . number_format($maintenance_cost, 0, ',', ' ') . " Ft/hó/albetét </td>";
    echo "<td>" . number_format($chimney_cost, 0, ',', ' ') . " Ft/hó/albetét </td>";
    echo "<td>" . number_format($cleaning_cost, 0, ',', ' ') . " Ft/hó/albetét </td>";
    echo "<td>" . number_format($renovation_cost, 0, ',', ' ') . " Ft/hó/albetét </td>";
    echo "<td>" . number_format($handling_cost, 0, ',', ' ') . " Ft/hó/albetét </td>";
    echo "<th> </th>";
    echo "</tr>";
    echo "<tr>";
    echo "<th>Közösköltség</th>";
    echo "<td>" . number_format($water_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($twater_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($junk_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($electrycity_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($gas_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($cam_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($costs_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($lift_cost, 0, ',', ' ') . " Ft/hó</td>";
    echo "<td>" . number_format($insurance_cost, 0, ',', ' ') . " Ft/hó </td>";
    echo "<td>" . number_format($bank_cost, 0, ',', ' ') . " Ft/hó </td>";
    echo "<td>" . number_format($maintenance_cost, 0, ',', ' ') . " Ft/hó </td>";
    echo "<td>" . number_format($chimney_cost, 0, ',', ' ') . " Ft/hó </td>";
    echo "<td>" . number_format($cleaning_cost, 0, ',', ' ') . " Ft/hó </td>";
    echo "<td>" . number_format($renovation_cost, 0, ',', ' ') . " Ft/hó </td>";
    echo "<td>" . number_format($handling_cost, 0, ',', ' ') . " Ft/hó </td>";
    echo "<th>" . number_format($ccosts, 0, ',', ' ') . " Ft/hó</th>";
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function getAllDepo() {
    $water_cost = $twater_cost = $junk_cost = $electrycity_cost = $gas_cost = $cam_cost = $costs_cost = $lift_cost = $m_water_cost = 0;
    $watermeter = "van";
    $sql = "SELECT `id`, `floor`, `door`, `area`, `garage_area`, "
            . "(`area`+`garage_area`) as areasum, `residents_no`, `area_ratio`, "
            . "`garage_area_ratio`, (`area_ratio`+`garage_area_ratio`) as ratiosum, "
            . "`watermeter`, `resident_name` FROM `deposits`;";
    $result1 = mysql_query($sql);
    if (!$result1)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit;
    }
    $deposit = array();
    $ccosts = array();
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
    $water = $fees[0];
    $twater = $fees[1];
    $junk = $fees[2];
    $electrycity = $fees[3];
    $gas = $fees[4];
    $cam = $fees[5];
    $costs = $fees[6];
    $lift = $fees[7];
    $insurance = $fees[8];
    $bank = $fees[9];
    $maintenance = $fees[10];
    $chimney = $fees[11];
    $cleaning = $fees[12];
    $renovation = $fees[13];
    $handling = $fees[14];
    
    $sumarea = $sumgarage =  $sumresidents = $sumarearatio = $sumgaragearearatio =
            $sumallarearatio = 0;
    for ($i = 0; $i < $deprows; $i++) {
        if ($deposit[$i]['watermeter'] == 0)
        {
            $water_cost = round((($water['yearly_amount'] / 12) / $water['dealer']) * $deposit[$i]['residents_no'], 0);
            $deposit[$i]['watermeter'] = "nincs";
        }
        else
        {
            $deposit[$i]['watermeter'] = "van";
        }
        $twater_cost = round((($twater['yearly_amount'] / 12) / $twater['dealer']), 0);
        $junk_cost = round(((($junk['yearly_amount'] / 12) / $junk['dealer']) * $deposit[$i]['ratiosum']), 0);
        $electrycity_cost = round(((($electrycity['yearly_amount'] / 12) / $electrycity['dealer']) * $deposit[$i]['ratiosum']), 0);
        $gas_cost = round(((($gas['yearly_amount'] / 12) / $gas['dealer']) * $deposit[$i]['area']), 0);
        $cam_cost = round(((($cam['yearly_amount'] / 12) / $cam['dealer']) * $deposit[$i]['ratiosum']), 0);
        $costs_cost = round(((($costs['yearly_amount'] / 12) / $costs['dealer']) * $deposit[$i]['ratiosum']), 0);
        $lift_cost = round(((($lift['yearly_amount'] / 12) / $lift['dealer']) * $deposit[$i]['ratiosum']), 0);
        $insurance_cost = round(((($insurance['yearly_amount'] / 12) / $insurance['dealer'])), 0);
        $bank_cost = round(((($bank['yearly_amount'] / 12) / $bank['dealer'])), 0);
        $maintenance_cost = round(((($maintenance['yearly_amount'] / 12) / $maintenance['dealer'])), 0);
        $chimney_cost = round(((($chimney['yearly_amount'] / 12) / $chimney['dealer'])), 0);
        $cleaning_cost = round(((($cleaning['yearly_amount'] / 12) / $cleaning['dealer'])), 0);
        $renovation_cost = round(((($renovation['yearly_amount'] / 12) / $renovation['dealer'])), 0);
        $handling_cost = round(((($handling['yearly_amount'] / 12) / $handling['dealer'])), 0);

        $deposit[$i]["ccost"] = $water_cost + $twater_cost + $junk_cost + $electrycity_cost + $gas_cost +
                $cam_cost + $costs_cost + $lift_cost + $insurance_cost + $bank_cost + $maintenance_cost +
                $chimney_cost + $cleaning_cost + $renovation_cost + $handling_cost;
       $sumarea += $deposit[$i]['area'];
       $sumgarage += $deposit[$i]['garage_area'];
       $sumallarea += $deposit[$i]['areasum'];
       $sumresidents += $deposit[$i]['residents_no'];
       $sumarearatio += $deposit[$i]['area_ratio'];
       $sumgaragearearatio += $deposit[$i]['garage_area_ratio'];
       $sumallarearatio += $deposit[$i]['ratiosum'];
    }
    //print_r($deposit);
    
    for ($i = 0; $i < $deprows; $i++) {
        
    }
    echo '<div class="content"><table id="results">';
    echo <<<EOT
<thead>
<tr>
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
   <th> Közösköltség </th>
   <th>  </th>
   <th> Részletek </th>
   <th> Módosítás </th>
</tr>
</thead>
   
EOT;
    foreach ($deposit as $row) {
        echo '<tbody>';
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value))
                if ($value > 999)
                {
                    echo '<td>' . number_format($value, 0, ',', ' ') . '<td>';
                }
                else
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
        
    }
    echo '<tr>';
    echo '<th colspan=3>Összesen:</th>';
    echo "<td>".number_format($sumarea, 0, ',', ' ')." m<sup>2</sup></td>";
    echo "<td>".number_format($sumgarage, 0, ',', ' ')." m<sup>2</sup></td>";
    echo "<td>".number_format(($sumarea+$sumgarage), 0, ',', ' ')." m<sup>2</sup></td>";
    echo "<td>$sumresidents fő</td>";
    echo "<td>".str_replace(".", ",", round($sumarearatio, 2))."</td>";
    echo "<td>".str_replace(".", ",", round($sumgaragearearatio, 2))."</td>";
    echo "<td>".str_replace(".", ",", round($sumallarearatio, 2))."</td>";
    echo '<th></th>';
    echo '<th></th>';
    echo '<th></th>';
    echo '<th></th>';
    echo '<th></th>';
    echo '<th></th>';
    echo '</tr>';
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}
