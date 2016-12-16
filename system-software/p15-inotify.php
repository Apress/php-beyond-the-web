<?php

# Turn this script into a daemon (see the start of this chapter for info)

$pid = pcntl_fork();

if ($pid == -1) { exit("Could not fork the child process"); };

if ($pid) { exit(0); };

if ( posix_setsid()  === -1 ) {
   exit("Could not become the session leader");
};

$pid = pcntl_fork();

if ($pid == -1) { exit("Could not fork child process into grandchild"); };

if ($pid) { exit(0); };

if (!fclose(STDIN)) { exit('Could not close STDIN'); };
if (!fclose(STDERR)) { exit('Could not close STDERR'); };
if (!fclose(STDOUT)) { exit('Could not close STDOUT'); };

$STDIN = fopen('/dev/null', 'r');
$STDOUT = fopen('/dev/null', 'w');
$STDERR = fopen('/var/log/our_error.log', 'wb');

$stayInLoop = true;

# Let the user know we are now up and running

`notify-send -i face-glasses 'File monitoring daemon started'`;

# We create an inotify instance to use

$inotify = inotify_init();

# We then add one or more "watches" to the instance. Each watch gives
# inotify a file or directory to monitor, and tells it which events to
# watch for. In this case, we want to watch the current directory
# (which PHP provides in the magic constant __DIR__ ), and we want to look
# for file accesses (IN_ACCESS) or (|) file modifications (IN_MODIFY).
# This is a "bit mask" of events, which will discuss later.

$watch = inotify_add_watch($inotify, __DIR__, IN_ACCESS | IN_MODIFY);

# Once our watch is set up, it will keep running, so we can enter our
# usual program loop and wait for inotify to tell us when an event occurs

while ($stayInLoop) {

# We now call inotify_read to get an array of file events that our
# watch has spotted. inotify_read blocks execution until an
# event occurs, so if nothing is happening our script will sit and
# wait at this point until it does.

  $events = inotify_read($inotify);

# inotify_read returns an array of events. Often this may just be an
# array with one event on a non-busy file/directory. However, multiple
# events may occur at the same time, and inotify queues events while
# your program is doing other things (like processing previous events)
# so you should always be prepared to handle multiple events. So we
# loop through the $events array...

  foreach ($events as $event) {

# The "mask" value tells us which type of event (or events)
# have occurred

    $type = $event["mask"];

# "name" gives us the file or directory name of the event

    $filename = $event["name"];

# We'll compose a human readable string to present to the user

    switch ($type) {

      case IN_ACCESS :
        $what =  "accessed";
        break;

      case IN_MODIFY :
        $what = "modified";
        break;

      case (INACCESS+IN_MODIFY) :
        $what = "accessed and modfied";

    };

# We'll now pop up a notification bubble with the event details

    `notify-send -i document '$filename was $what'`;

# Finally, if a file called bye.txt was accessed or modified,
# we'll exit our loop and exit the daemon

     if ($filename == 'bye.txt') { $stayInLoop = false; };
  };

};

`notify-send -i face-raspberry 'File monitoring daemon stopped'`;

exit(0);
