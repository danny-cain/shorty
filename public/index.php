<?php

header("Shorty-HTTP-Ver: 1");

error_reporting(E_ALL);
ini_set('display_errors', true);

// after this include we can start using interfaces etc
require dirname(__FILE__).'/initialise.php';

use CannyDain\Shorty\Execution\ShortyMain;

$main = new ShortyMain();

ShortyInit::Initialise(array($main));
$main->main();