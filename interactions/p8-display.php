<?php

$segment = shmop_open('1234456', 'c', 0755, 1024);

while (1) {

  sleep(1);

  $size = shmop_size($segment);

  $jsonArray = shmop_read($segment, 0, $size);

  echo("Fetched $size bytes of data at ".date("H:i:s").
  ". Our random numbers are :\n");

  print_r(json_decode(trim($jsonArray)));

};
