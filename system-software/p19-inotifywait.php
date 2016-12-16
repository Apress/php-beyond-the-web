<?php

# Create the command-line string to execute inotifywait with the options
# we want. In this case, we use the following options :
#
#   --csv : Returns the output in easy-to-parse csv format
#  -q : Suppresses any messages and only shows the event output
#  -r : Run in recursive mode, so all sub-directories are included
#  -m : Run in "monitor" mode, which simply means it runs continuously
#  -e : Specifies which events to listen for (modify and delete)
#
# Finally we give it the directory to watch (/home). This could be just
# '/' if you want to watch the whole filesystem.

$command = 'inotifywait --csv -q -r -m -e modify -e delete /home';

# Because we want to start displaying the events as they happen, rather
# than after the command finishes (as it won't finish), we can't use
# methods like backticks or shell_exec() to run the script. Instead we use
# popen() to treat it like a file stream.

$handle = popen($command, 'r');

# read each line of output as it occurs

while ($line = fgets($handle)) {

# The data is in CSV format (as we used --csv), so parse it into
# a PHP array

  $event = str_getcsv($line);

# Output details of the event to the shell

  echo 'A '.$event[1].' event occured in Directory '.$event[0];
  echo ' on file '.$event[2]."\n";

};
