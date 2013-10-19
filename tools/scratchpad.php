<?php

$_SERVER['SERVER_NAME'] = 'danny.shorty.goblin';
$_SERVER['REQUEST_METHOD'] = 'GET';

require dirname(dirname(__FILE__)).'/public/initialise.php';

$paths = array
(
    '/_web/shorty/tools/',
    '/_web/shorty/tools',
    '/_web\\shorty\\tools',
    '/_web//shorty/tools',
    '/test/test/test',
    '/_web//shorty/tools/../tools'
);

foreach ($paths as $path)
{
    echo realpath($path)."\r\n";
}