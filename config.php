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
$house['payment_day'] = 15;
$house['deposit_id'] = 65;
$house['bank_account'] = 'OTP Bank 11709002-20073022';

if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = NULL;
}
