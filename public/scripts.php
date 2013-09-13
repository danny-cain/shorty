<?php

// get autoloader included (for when we start minifying and gzipping)
require dirname(__FILE__) . '/initialise.php';

header("Content-Type: application/javascript", true);

$minifier = new \CannyDain\Lib\Performance\Minifier\MinifyJS();
$code = $minifier->bundleDirectory(dirname(__FILE__).'/scripts/');

echo $code;