<?php

# include() a php file from the bundle

include('phar://home/rob/mybundle.phar/sample.php');

# or load some csv data from the bundle into a variable

$data = file_get_contents('phar://home/rob/mybundle.phar/data.csv');

# or stream an image from the bundle out to the browser

$resource = 'phar://home/rob/mybundle.phar/images/cat.jpg';

$fp = fopen($resouce, 'rb');

header("Content-Type: image/jpeg");

header("Content-Length: " . filesize($resource));

fpassthru($fp);
