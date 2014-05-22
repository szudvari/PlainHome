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

function connectDbIso() {
    global $db;
    $con = mysql_connect($db['host'], $db['user'], $db['pass']);

    if (!$con)
    {
        die('Nem tudok kapcsolódni: ' . mysql_error());
    }
    mysql_select_db($db['name'], $con);
    mysql_set_charset("latin2", $con);
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

function insertHirlevTable($table, $vars, $con) {
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

function insertMainTable($array, $title, $type, $user, $con) {
    $sql = "INSERT INTO  hirlevel (cim ,datum ,hirlevel_tipus, created_on, created_by)"
            . " values (\"$title\", \"{$array['sendingdate']}\", \"$type\", NOW(), \"$user\");";
    mysql_query($sql, $con);
    $result = mysql_insert_id();
    return $result;
}

function getANewsletter($table, $id) {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT * from $table where hirlev_id=$id;";
    $result = mysql_query($sql);
    $array = array();
    while ($row = mysql_fetch_assoc($result)) {
        $array = $row;
    }
//print_r($array);

    return $array;
}

function getANewsletterIso($table, $id) {
    mysql_query("set names 'latin2'");
    mysql_query("set character set 'latin2'");
    $sql = "SELECT * from $table where hirlev_id=$id;";
    $result = mysql_query($sql);
    $array = array();
    while ($row = mysql_fetch_assoc($result)) {
        $array = $row;
    }
//print_r($array);

    return $array;
}

function newsletters() {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT hirlevel.id, hirlevel.cim, hirlevel.datum, hirlevel.hirlevel_tipus, "
            . "hirlevel.created_on, users.user "
            . "from hirlevel "
            . "left join users on hirlevel.created_by=users.id "
            . "order by hirlevel.id desc";
    $result = mysql_query($sql);
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    echo '<table id="results">';
    echo <<<EOT
   <th> id </th>
   <th> Cím </th>
   <th> Kiküldés dátuma </th>
   <th> Hírlevél típusa </th>
   <th> Készítés ideje </th>
   <th> Létrehozta </th>
   <th> Megnéz </th>
   <th> Szerkeszt </th>
   <th> HTML mentés </th>
   <th> TXT mentés </th>
   
EOT;
    foreach ($table as $row) {
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value) && ($value < 100))
            {
                if ($value == 1)
                {
                    echo "<td>Travelo hírlevél</td>";
                }
                elseif ($value == 2)
                {
                    echo "<td>Life hírlevél</td>";
                }
                elseif ($value == 3)
                {
                    echo "<td>Life egyképes hírlevél</td>";
                }
            }
            else
            {
                echo '<td>' . $value . '</td>';
            }
        }
        switch ($row['hirlevel_tipus']) {
            case 1:
                $link = "travelo_nl_db.php";
                break;
            case 2:
                $link = "life_nl_db.php";
                break;
            case 3:
                $link = "life_op_db.php";
                break;
        }

        echo "<td><a href=\"$link?hirlevel_id={$row['id']}\" target=\"blank\">Megnéz</a></td>";
        echo "<td><a href=\"newsletter_edit.php?hirlevel_id={$row['id']}&hirlevel_type={$row['hirlevel_tipus']}\" target=\"blank\">Szerkeszt</a></td>";
        echo "<td><a href=\"$link?hirlevel_id={$row['id']}&save=1\" target=\"blank\">HTML kód mentése</a></td>";
        switch ($row['hirlevel_tipus']) {
        case 1:
                $link = "travelo_nl_db_txt.php";
                break;
            case 2:
                $link = "life_nl_db_txt.php";
                break;
            case 3:
                $link = "life_op_db_txt.php";
                break;
        }
        echo "<td><a href=\"$link?hirlevel_id={$row['id']}\" target=\"blank\">TXT változat mentése</a></td>";
        echo '</tr>';
    }
    echo '</table>';
}

function updateHirlevTable($table, $vars, $id, $con) {
    foreach ($vars as $key => $value) {
        if (is_numeric($value))
            $update_list[] = "$key = $value";
        else
            $update_list[] = $key . " = '" . $value . "'";
    }
    $update_list = implode(", ", $update_list);

    $sql = "UPDATE " . $table . " SET " . $update_list . " WHERE hirlev_id = " . $id;
    mysql_query($sql, $con);
    if (!mysql_query($sql, $con))
    {
        die('hiba a frissítés során' . mysql_errno() . ':' . mysql_error());
    }
}

function getSuccesNewsletter($id) {
    mysql_query("set names 'utf8'");
    mysql_query("set character set 'utf8'");
    $sql = "SELECT hirlevel.id, hirlevel.cim, hirlevel.datum, hirlevel.hirlevel_tipus, "
            . "hirlevel.created_on, users.user "
            . "from hirlevel "
            . "left join users on hirlevel.created_by=users.id "
            . "where hirlevel.id=$id;";
    $result = mysql_query($sql);
    $table = array();
    while ($row = mysql_fetch_assoc($result)) {
        $table[] = $row;
    }
    echo '<table id="results">';
    echo <<<EOT
   <th> id </th>
   <th> Cím </th>
   <th> Kiküldés dátuma </th>
   <th> Hírlevél típusa </th>
   <th> Készítés ideje </th>
   <th> Létrehozta </th>
   <th> Megnéz </th>
   <th> Szerkeszt </th>
   <th> HTML mentése </th>
   <th> TXT mentése </th>
   
EOT;
    foreach ($table as $row) {
        echo '<tr>';
        foreach ($row as $value) {
            if (is_numeric($value) && ($value < 10))
            {
                if ($value == 1)
                {
                    echo "<td>Travelo hírlevél</td>";
                }
                elseif ($value == 2)
                {
                    echo "<td>Life hírlevél</td>";
                }
                elseif ($value == 3)
                {
                    echo "<td>Life egyképes hírlevél</td>";
                }
            }
            else
            {
                echo '<td>' . $value . '</td>';
            }
        }
        switch ($row['hirlevel_tipus']) {
            case 1:
                $link = "travelo_nl_db.php";
                break;
            case 2:
                $link = "life_nl_db.php";
                break;
            case 3:
                $link = "life_op_db.php";
                break;
        }
        echo "<td><a href=\"$link?hirlevel_id={$row['id']}\" target=\"_blank\">Megnéz</a></td>";
        echo "<td><a href=\"newsletter_edit.php?hirlevel_id={$row['id']}&hirlevel_type={$row['hirlevel_tipus']}\" target=\"_blank\">Szerkeszt</a></td>";
        echo "<td><a href=\"$link?hirlevel_id={$row['id']}&save=1\" target=\"blank\">HTML mentése</a></td>";
        switch ($row['hirlevel_tipus']) {
        case 1:
                $link = "travelo_nl_db_txt.php";
                break;
            case 2:
                $link = "life_nl_db_txt.php";
                break;
            case 3:
                $link = "life_op_db_txt.php";
                break;
        }
        echo "<td><a href=\"$link?hirlevel_id={$row['id']}\" target=\"_blank\">TXT mentése</a></td>";
        echo '</tr>';
    }
    echo '</table>';
}

