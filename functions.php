<?php

include_once 'db.php';
include_once 'config.php';

function auto_copyright($year = 'auto') {
    if (intval($year) == 'auto')
    {
        $year = date('Y');
    }
    if (intval($year) == date('Y'))
    {
        $cr = intval($year);
    }
    if (intval($year) < date('Y'))
    {
        $cr = intval($year) . ' - ' . date('Y');
    }
    if (intval($year) > date('Y'))
    {
        $cr = date('Y');
    }
    echo "JNF - ($cr)";
}

function encodePass($clearPass) {
    $salt1 = "84ubbd%mkei";
    $salt2 = "+jhbeza%oop";
    $saltedText = $salt1 . $clearPass . $salt2;
    $cryptedPass = hash("sha512", $saltedText);
    return $cryptedPass;
}

function spamcheck($field) {
    // Sanitize e-mail address
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);
    // Validate e-mail address
    if (filter_var($field, FILTER_VALIDATE_EMAIL))
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function sendMessageToNewUser($user) {
    global $house;
    $message = <<<EOT
Kedves {$user['firstname']} {$user['lastname']}!
    
Üdvözöljük a {$house['name']} társasház online rendszerében!

Belépés után tájékozódhat a házat érintő általános ügyekről, aktuális közösköltségéről.

Az ön felhasználóneve: {$user['username']}
Az ön jelszava: {$user['pass']}

Jelszavát belépés után a "Saját adataim" menüpontban tudja megváltoztatni!

Belépéshez, kérem, látogasson el a {$house['webpage']} weboldalra!
EOT;

    $subject = "PlainHouse regisztráció -" . $house['name'];
    $from = $house['infomail'];

    mail($user['email'], $subject, $message, "From: $from\n");
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function round_to_nearest_n($int, $n) {
    return round($int / $n) * $n;
}

function filesInDirectory ($dir) {
$files = array_diff(scandir($dir), array('..', '.'));
return $files;
}

function deleteFile ($file) {
   if (!unlink($file))
  {
  echo ("Error deleting $file");
  }
}

function sendMessageAfterUpdatePassword($user) {
    global $house;
    $message = <<<EOT
Kedves {$user['firstname']} {$user['lastname']}!
    
Belépési jelszava a {$house['name']} társasház online rendszerébe megváltozott!

Ezentúl az alábbi adatokkal tud a PlainHouse - {$house['name']} rendszerébe belépni:

Az ön felhasználóneve: {$user['username']}
Az ön új jelszava: {$user['pass']}
   
Belépés után tájékozódhat a házat érintő általános ügyekről, aktuális közösköltségéről.

Jelszavát belépés után bármikor a "Saját adataim" menüpontban tudja megváltoztatni!

Belépéshez, kérem, látogasson el a {$house['webpage']} weboldalra!
EOT;

    $subject = "PlainHouse jelszó változtatás -" . $house['name'];
    $from = $house['infomail'];

    mail($user['email'], $subject, $message, "From: $from\n");
}