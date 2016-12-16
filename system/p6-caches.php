<?php

# Create a file and add some text to it

$filename = 'test.txt';

$handle = fopen($filename, 'w+');

fwrite($handle, 'test');

# The following should print 4

echo stat($filename)["size"]."\n";

# Now write some data to the file, increasing the file size.

fwrite($handle, 'more test');

# Intuitively, the following command should print 13 as the file is now
# bigger than before. However it still prints 4, because the filesize
# value for this file is now cached.

echo stat($filename)["size"]."\n";

# If we clear the cache ....

clearstatcache();

# then the next line should print 13 as expected

echo stat($filename)["size"]."\n";

fclose($handle);
