<?php

$filename = 'bigfile.csv';

$memory1 = memory_get_usage();

$file_string = file_get_contents($filename);

$memory2  = memory_get_usage();

unset($file_string);

$memoryBase = memory_get_usage();

$file_handle = fopen($filename, 'r');

while ($line = fgets($file_handle)) {

  $memoryCurrent = memory_get_usage();

  if ($memoryCurrent > $memoryBase) { $memoryHigh = $memoryCurrent;};

};

echo("Memory used by single string : ".($memory2-$memory1)." bytes\n");

echo("Max memory used when reading by line : ".
  ($memoryHigh-$memoryBase)." bytes\n");
