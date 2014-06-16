<?php

session_start();
include_once 'db.php';
include_once 'config.php';
include_once 'functions.php';
include_once 'html.php';
/* * ***************************** */
/* Code at http://legend.ws/blog/tips-tricks/csv-php-mysql-import/
  /* Edit the entries below to reflect the appropriate values
  /******************************* */
//$file=$_FILES["file"]["tmp_name"];
move_uploaded_file($_FILES["file"]["tmp_name"], "documents/" . $_FILES["file"]["name"]);
$databasetable = "payment";
$fieldseparator = ";";
$lineseparator = "\n";
$csvfile = "./documents/{$_FILES['file']['name']}";
/* * ***************************** */
/* Would you like to add an ampty field at the beginning of these records?
  /* This is useful if you have a table with the first field being an auto_increment integer
  /* and the csv file does not have such as empty field before the records.
  /* Set 1 for yes and 0 for no. ATTENTION: don't set to 1 if you are not sure.
  /* This can dump data in the wrong fields if this extra field does not exist in the table
  /******************************* */
$addauto = 1;
/* * ***************************** */
/* Would you like to save the mysql queries in a file? If yes set $save to 1.
  /* Permission on the file should be set to 777. Either upload a sample file through ftp and
  /* change the permissions, or execute at the prompt: touch output.sql && chmod 777 output.sql
  /******************************* */
$save = 0;
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

    $lines = 1;
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

        if ($addauto)
            $query = "insert into $databasetable values('','$linemysql');";
        else
            $query = "insert into $databasetable values('$linemysql');";

        $queries .= $query . "\n";

        @mysql_query($query);
    }

    @mysql_close($con);

    if ($save) {

        if (!is_writable($outputfile)) {
            echo "File is not writable, check permissions.\n";
        } else {
            $file2 = fopen($outputfile, "w");

            if (!$file2) {
                echo "Error writing to the output file.\n";
            } else {
                fwrite($file2, $queries);
                fclose($file2);
            }
        }
    }
    deleteFile($csvfile);
    echo "Sikeres feltöltés. $lines sort tartalmazott a csv file.\n";
} else {
    notLoggedIn();
}
ob_end_flush();
