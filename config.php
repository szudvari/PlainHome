<?php
$db['host'] = "localhost";
$db['name'] = "plainhouse";
$db['user'] = "root";
$db['pass'] = "root";
$db['charset'] = "utf8";

$website['title'] = "PlainHome";
$website['version'] = "v0.0.0 alpha";

$house['name'] = "Pöttyös utca 4.";
$house['infomail'] = "szudvari@gmail.com";

if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = NULL;
}
