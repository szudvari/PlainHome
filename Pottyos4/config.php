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
$house['webpage'] = "http://pottyos4.hu";
$house['payment_day'] = 15;
$house['deposit_id'] = 65;
$house['bank_account'] = 'OTP Bank 11709002-20073022';

if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = NULL;
}
