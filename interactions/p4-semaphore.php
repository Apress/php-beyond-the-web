<?php

$process = getmypid();

$semaphore = sem_get('123456', 1, 0666, 1);

if (!$semaphore) { echo("Couldn't get semaphore\n"); exit;};

while (1) {

  sem_acquire($semaphore);

  echo ($process." has the semaphore\n");

  sleep(rand(0,5));

  if (!sem_release($semaphore)) {

    echo("Couldn't release semaphore\n");

    exit;

  };

  echo ($process." has released the semaphore\n");

};
