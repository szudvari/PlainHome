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
    mysql_query($sql, $con);

    $result = mysql_insert_id();
    return $result;
}

function listDeposits() {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT * from deposits;";
    $result = mysql_query($sql);
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    echo '<table id="results">';
    echo <<<EOT
   <th> id </th>
   <th> Emelet </th>
   <th> Ajtó </th>
   <th> Terület (nm) </th>
   <th> Lakók száma </th>
   <th> Megjegyzés </th>
   <th> Módosítás </th>
   
   
EOT;
    foreach ($table as $row) {
        echo '<tr>';
        foreach ($row as $value) {
            echo '<td>' . $value . '</td>';
        }
        echo "<td><a href=\"#\" target=\"blank\">Módosít</a></td>";
        echo '</tr>';
    }
    echo '</table>';
}

function insertDepoDb($deposit, $con) {
    $sql = "INSERT INTO  deposits (floor ,door ,area, residents_no, note) values "
            . "(\"{$deposit['floor']}\", \"{$deposit['door']}\", \"{$deposit['area']}\", "
            . "\"{$deposit['residents']}\", \"{$deposit['note']}\")";
    $res = mysql_query($sql, $con);
    if (!$res)
    {
        echo mysql_errno() . ": " . mysql_error();
        exit();
    }
}
