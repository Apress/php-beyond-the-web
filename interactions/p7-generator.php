<?php

$segment = shmop_open('1234456', 'c', 0755, 1024);

for ($counter=0; $counter < 20; $counter++) {

$jsonArray = json_encode(

  array(rand(0,50000),
  rand(0,2000),
  rand(5000,100000))

);

$jsonArray = str_pad($jsonArray, 1024-strlen($jsonArray), ' ');

$dataSize = strlen($jsonArray);

$bytesWritten = shmop_write($segment, $jsonArray, 0);

if (!$bytesWritten) { echo("Error - couldn't write to memory\n"); };

if ($dataSize != $bytesWritten) {
  echo("Error - couldn't write all data to memory\n");
};

sleep(1);

};

shmop_delete($segment);

shmop_close($segment);
