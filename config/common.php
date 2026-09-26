<?php

session_start();

define("BASE_DIR", $_SERVER["DOCUMENT_ROOT"] . "/optics/");



$configFile = BASE_DIR . "config/.env";

$configRows = file($configFile);

foreach($configRows as $c) {
    $key = explode("=", $c)[0];
    $value = explode("=", $c)[1];

    define($key, trim($value));
}



function setFlash($key, $value) {
    $_SESSION[$key] = $value;
}

function getFlash($key) {
    if(!hasFlash($key)) {
        return null;
    }

    $flashData = $_SESSION[$key];

    unset($_SESSION[$key]);

    return $flashData;
}

function hasFlash($key) {
    return isset($_SESSION[$key]) && $_SESSION[$key];
}

function get($key) {
    
    if(isset($_GET[$key])) {
        return $_GET[$key];
    }

    return null;
}

function post($key) {
    
    if(isset($_POST[$key])) {
        return $_POST[$key];
    }

    return null;
}

//log.txt
function logAccess($page) {
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/optics/config/data/log.txt';
    $timestamp = date('Y-m-d');
    $user = isset($_SESSION['user']) ? $_SESSION['user']->email : 'Guest';
    $entry = "{$user};{$page};{$timestamp}\n";

    file_put_contents($logFile, $entry, FILE_APPEND);
}



?>