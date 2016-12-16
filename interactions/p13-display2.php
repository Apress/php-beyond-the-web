<?php

$queue = msg_get_queue('1234456', 0666);

while (1) {

  sleep(1);

  if (!msg_receive($queue, -4, $realType, 1024, $phpArray, true, 0, $errorCode)) {
    echo("Error - Couldn't receive message - code $errorCode\n");
  };

  echo("Fetched message at ".date("H:i:s").". Random numbers are :\n");

  print_r($phpArray);

};
