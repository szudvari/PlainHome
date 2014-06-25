<?php
$db['host'] = "localhost";
$db['name'] = "webariel";
$db['user'] = "webariel";
$db['pass'] = "OrkosAdatbazisa";
$db['charset'] = "utf8";

$website['title'] = "PlainHouse";
$website['version'] = "1.0";

$house['name'] = "Kossuth utca 3.";
$house['infomail'] = "info@webariel.hu";
$house['webpage'] = "http://webariel.hu/demo/plainhouse";
$house['payment_day'] = 15;
$house['deposit_id'] = 65;
$house['bank_account'] = 'OTP Bank 12345678-12345678';

if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = NULL;
}
