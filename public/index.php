<?php

header("Shorty-HTTP-Ver: 1");

logRequest();

// after this include we can start using interfaces etc
require dirname(__FILE__).'/initialise.php';

error_reporting(E_ALL);
ini_set('display_errors', true);


use CannyDain\Shorty\Execution\ShortyMain;

$main = new ShortyMain();

ShortyInit::Initialise(array($main));
$main->main();

function logRequest()
{
    $logData = array
    (
        'GET' => $_GET,
        'POST' => $_POST,
        'COOKIES' => $_COOKIE
    );

    file_put_contents(getLogFile(), json_encode($logData));
}

function getLogFile()
{
    $dir = dirname(__FILE__).'/logs/';
    if (!file_exists($dir))
        mkdir($dir, 0777, true);

    $filename = 'log-'.date('Y-m-d-H-i-s');
    $num = 1;
    $ext = '.log';

    if (!file_exists($dir.$filename.$ext))
        return $dir.$filename.$ext;

    while (file_exists($dir.$filename.'-'.$num.$ext))
    {
        $num ++;
    }

    return $dir.$filename.'-'.$num.$ext;
}