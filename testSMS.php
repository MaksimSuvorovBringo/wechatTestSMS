<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include './vendor/autoload.php';

if (isset($argv[1])) {

    $number = substr($argv[1],-10);
    $userToLogin = new AccountUser('+7'.$number);

} else {

    $userToLogin = new AccountUser('+70000000000');//Your phone number to get SMS
}

$userToLogin->getVerifyCode();