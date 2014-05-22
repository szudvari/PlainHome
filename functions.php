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

