<?php

require dirname(dirname(__FILE__)).'/classes/cannydain/initialise.php';

$file = dirname(__FILE__).'/test.pdf';
if (file_exists($file))
    unlink($file);

