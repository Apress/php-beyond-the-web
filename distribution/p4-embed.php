<?php

# Open a pointer to this file using the magic constant __FILE__

$thisfile = fopen(__FILE__, 'r');

# Seek down to the 1st byte after the __halt_compiler(); instruction. This
# is contained in the automatically created __COMPILER_HALT_OFFSET__
# constant.

fseek($thisfile, __COMPILER_HALT_OFFSET__);

# Lets grab everything that follows that ...

$ourtext = stream_get_contents($thisfile);

# and write it out to a new file.

file_put_contents('textfile.txt', $ourtext);

# Our scripts stops here.

__halt_compiler();The additional content starts here.

This is the text file.
It would normally cause a PHP fatal syntax error if
this text was simply dumped into a PHP file.
