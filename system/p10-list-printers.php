<?php

$keyHandle = reg_open_key('HKEY_CURRENT_USER',
  'Software\Microsoft\Windows NT\CurrentVersion\PrinterPorts');

if ($keyHandle) {

  $subkeys = reg_enum_key($keyHandle);

  foreach ($subkeys as $index => $subkey) {

    echo "Printer $index is $subkey \n";

  };

  reg_close_key($keyHandle);

} else { die ("Couldn't open Registry key"); };
