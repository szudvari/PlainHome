<?php
$db['host'] = "localhost";
$db['name'] = "plainhouse";
$db['user'] = "root";
$db['pass'] = "root";
$db['charset'] = "utf8";

$website['title'] = "PlainHouse";
$website['version'] = "0.1a";

$house['name'] = "Pöttyös utca 4.";
$house['infomail'] = "szudvari@gmail.com";
$house['webpage'] = "http://users.ininet.hu/plainhouse";

if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = NULL;
}
