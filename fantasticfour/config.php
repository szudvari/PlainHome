<?php
$db['host'] = "127.0.0.1";
$db['name'] = "fantasticfour";
$db['user'] = "fantasticfour";
$db['pass'] = "4444Fantasztikus";
$db['charset'] = "utf8";

$website['title'] = "PlainHome";
$website['version'] = "v0.0.0 alpha";

$house['name'] = "Pöttyös utca 4.";
$house['infomail'] = "szudvari@gmail.com";

if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = NULL;
}
