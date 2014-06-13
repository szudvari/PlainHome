<?php
$db['host'] = "localhost";
$db['name'] = "pottyos4_plainhouse";
$db['user'] = "pottyos4_ph";
$db['pass'] = "Syjoe982";
$db['charset'] = "utf8";

$website['title'] = "PlainHouse";
$website['version'] = "1.0";

$house['name'] = "Pöttyös utca 4.";
$house['infomail'] = "info@pottyos4.hu";
$house['webpage'] = "http://users.ininet.hu/plainhouse";
$house['payment_day'] = 15;
$house['deposit_id'] = 65;

if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = NULL;
}
