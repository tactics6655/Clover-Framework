<?php

include_once("./../vendor/autoload.php");

use Xanax\Classes\Web\InternetProtocol as IP;
use Xanax\Classes\Protocol\Internet as InternetProtocol;

$filename = 'example.gif';

if (!$fp = @fopen($filename, 'rb')) {
    return false;
}

$signature = fread($fp, 3);
$version = fread($fp, 3);
$screen_descriptor = fread($fp, 7);

$screen_width = ((ord($screen_descriptor[1])) << 8) +
    ((ord($screen_descriptor[0])));

$screen_height = ((ord($screen_descriptor[3])) << 8) +
    ((ord($screen_descriptor[2])));

$global_color_table_size = (ord($screen_descriptor[4]) + ord($screen_descriptor[5]));
$global_color_table_size = 3 * (2 ^ ($global_color_table_size + 1));

echo $global_color_table_size;
