<?php

// get autoloader included (for when we start minifying and gzipping)
require dirname(__FILE__) . '/initialise.php';

header("Content-Type: text/css", true);
$minifier = new \CannyDain\Lib\Performance\Minifier\MinifyCSS();
echo $minifier->bundleAndMinifyDirectory(dirname(__FILE__).'/styles/');