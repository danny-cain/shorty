<?php

header("Shorty-HTTP-Ver: 1");

// after this include we can start using interfaces etc
require dirname(__FILE__).'/initialise.php';

error_reporting(E_ALL);
ini_set('display_errors', true);


use CannyDain\Shorty\Execution\ShortyMain;

$main = new ShortyMain();

ShortyInit::Initialise(array($main));
$main->main();