<?php

date_default_timezone_set('Factory');

$time = date('H:i:s');
$gmtime = gmdate('H:i:s');

echo 'UTC: '.$time."\r\n";
echo "Local: ".$gmtime."\r\n";