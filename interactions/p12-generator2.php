<?php

$queue = msg_get_queue('1234456', 0666);

for ($counter=0; $counter < 20; $counter++) {

  $phpArray = array(rand(0,50000), rand(0,2000), rand(5000,100000));

  if (!msg_send($queue, 3, $phpArray, true, true, $errorCode)) {

    echo("Error - couldn't send message - code $errorCode\n");

  };

};
