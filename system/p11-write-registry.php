<?php

$keyHandle = reg_open_key('HKEY_CURRENT_USER',
  'Software\My Php Software Co\My Software\login');

if ($keyHandle) {

  reg_set_value($keyHandle, 'username', REG_SZ, 'rob');

  reg_close_key($keyHandle);

} else { die ("Couldn't open Registry key"); };
