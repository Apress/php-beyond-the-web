<?php

# Let's fill some variables using loops

$something = $anotherthing = '';

# Let's create a "checkpoint" by recording the current time and memory
# usage

$time1 = microtime(true);
$memory1 = memory_get_usage();

# Now let's do a loop 10 times, having a quick usleep and adding just a
# little data to our variable each time

for ($counter = 0; $counter < 10; $counter++) {
   usleep(10);
   $something .= 'a';
};

# Now create a second checkpoint

$memory2 = memory_get_usage();
$time2 = microtime(true);

# Let's do this second loop 1000 times, having a longer sleep and adding
# lots of data to our variable each time

for ($counter = 0; $counter < 1000; $counter++) {
   usleep(100);
   $anotherthing .= str_repeat('abc',1000);
};

# and create a final checkpoint

$memory3 = memory_get_usage();
$time3 = microtime(true);

# Now let's output the time and memory each loop used.

echo ("1st Loop : ".($time2-$time1)." msecs, ".
($memory2-$memory1)." bytes\n");

echo ("2nd Loop : ".($time3-$time2)." msecs, ".
($memory3-$memory2)." bytes\n");

echo ("Peak memory usage : ". memory_get_peak_usage()." bytes\n");
