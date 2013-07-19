<?php

if (!isset($_SERVER['SHORTY_SITE']))
{
    echo 'Site variable not set. Please use set SHORTY_SITE={siteName} before executing this script.';
    exit;
}

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SERVER_NAME'] = $_SERVER['SHORTY_SITE'];
require dirname(dirname(__FILE__)).'/public/initialise.php';