<?php

$filename = 'bigfile.csv';

echo("Size of file : ".filesize($filename)." bytes\n");

$memory1 = memory_get_usage();

$file_array = file($filename);

$memory2 = memory_get_usage();

$file_string = file_get_contents($filename);

$memory3  = memory_get_usage();

echo("Memory used by array : ".($memory2-$memory1)." bytes\n");

echo("Memory used by string : ".($memory3-$memory2)." bytes\n");
