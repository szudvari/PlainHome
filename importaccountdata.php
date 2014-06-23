<?php

session_start();
include_once 'db.php';
include_once 'config.php';
include_once 'functions.php';
include_once 'html.php';

move_uploaded_file($_FILES["file"]["tmp_name"], "documents/" . $_FILES["file"]["name"]);
$databasetable = "payment";
$fieldseparator = ";";
$lineseparator = "\n";
$csvfile = "./documents/{$_FILES['file']['name']}";
$outputfile = "./documents/output.sql";
/* * ***************************** */
ob_start();
htmlHead($website['title'], $house['name']);
webheader($_SESSION);
if ($_SESSION["admin"] > 0) {

    if (!file_exists($csvfile)) {
        echo "File not found. Make sure you specified the correct path.\n";
        exit;
    }

    $file = fopen($csvfile, "r");

    if (!$file) {
        echo "Error opening data file.\n";
        exit;
    }

    $size = filesize($csvfile);

    if (!$size) {
        echo "File is empty.\n";
        exit;
    }

    $csvcontent = fread($file, $size);

    fclose($file);

    $con = connectDb();

    $lines = 0;
    $queries = "";
    $linearray = array();

    foreach (split($lineseparator, $csvcontent) as $line) {

        $lines++;

        $line = trim($line, " \t");

        $line = str_replace("\r", "", $line);

        /*         * **********************************
          This line escapes the special character. remove it if entries are already escaped in the csv file
         * ********************************** */
        $line = str_replace("'", "\'", $line);
        /*         * ********************************** */

        $linearray = explode($fieldseparator, $line);

        $linemysql = implode("','", $linearray);

        $query = "insert into $databasetable (`deposit_id`, `date`, `amount`, `account_date`, `user`) values ('$linemysql');";
        
        $queries .= $query . "\n";
        //echo $query;
        $result=mysql_query($query);
        
    }

    deleteFile($csvfile);
    $year = getLatestpaymentAccDate ();
    updateAllBalance($year);
    closeDb($con);
    echo "Sikeres feltöltés. $lines sort tartalmazott a csv file.\n";
} else {
    notLoggedIn();
}
ob_end_flush();
