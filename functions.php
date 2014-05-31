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
  $field=filter_var($field, FILTER_SANITIZE_EMAIL);
  // Validate e-mail address
  if(filter_var($field, FILTER_VALIDATE_EMAIL)) {
    return TRUE;
  } else {
    return FALSE;
  }
}
function sendMessageToNewUser ($user) {
global $house;
    $message = <<<EOT
Kedves {$user['firstname']} {$user['lastname']}!
    
Üdvözöljük a {$house['name']} társasház online rendeszerében!

Belépés után tájékozódhat a házat érintő általános ügyekről, aktuális közösköltségéről.

Az ön felhasználóneve: {$user['username']}
Az ön jelszava: {$user['pass']}

Belépéshez, kérem, látogasson el a {$house['webpage']} weboldalra!
EOT;

$subject = "PlainHouse regisztráció -". $house['name'];
$from = $house['infomail'];

mail($user['email'], $subject, $message, "From: $from\n");
}