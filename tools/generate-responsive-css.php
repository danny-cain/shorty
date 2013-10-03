<?php

$script = array_shift($argv);
if (count($argv) < 2)
{
    echo 'Responsive CSS Generation Script'."\r\n";
    echo "Copyright 2013 Danny Cain\r\n";
    echo "-------------------------\r\n";
    echo "Usage: php generate-responsive-css.php <columns> <margin>\r\n";
    exit;
}

$columnCount = intval(array_shift($argv));
$margin = floatval(array_shift($argv));
$columnWidth = (100 - (($columnCount - 1) * $margin)) / $columnCount;

echo <<<CSS
.layoutCell
{
    display: inline-block;
    width: {$columnWidth}%;
    margin-left: {$margin}%;
    vertical-align: top;
}

.layoutCell:first-child
{
    margin-left: 0;
}

CSS;

for ($i = 2; $i <= $columnCount; $i ++)
{
    $width = ($columnWidth * $i) + (($i - 1) * $margin);
    echo <<<CSS
.span-{$i}
{
    width: {$width}%;
}

CSS;

}