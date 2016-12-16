<?php

# We first have to "open" a key before we can do anything with it.

$keyHandle = reg_open_key('HKEY_CURRENT_USER',
   'Software\Mozilla\Firefox\Crash Reporter');

if ($keyHandle) {

  $email = reg_get_value($keyHandle, 'Email');

  echo ("Crash Reporter Email  : $email\n");

  reg_close_key($keyHandle);

} else { die ("Couldn't open Registry key"); };
