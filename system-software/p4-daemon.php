<?php

# We start by forking this script, which creates the child process.

$pid = pcntl_fork();

# The child process will start running from here and will be a copy of the
# parent process, which includes all opened resources, variable values and
# so on, with the sole exception of the $pid variable above which is not
# set for the child. The parent process will keep running from here as
# well, with the process ID of the child process assigned to $pid

# If for some reason a child process could not be forked, for example
# the system is low on memory, $pid will be set to -1

if ($pid == -1) { exit("Could not fork the child process"); };

# If $pid is set to a process ID, then we must be the parent, and
# should exit.

if ($pid) { exit(0); };

# As the parent has exited above, the following code is now executed
# solely by the child process.

# We detach from the TTY (terminal) by becoming the "session leader"
# (instead of the TTY being leader. This starts a new POSIX session) ...

if ( posix_setsid()  === -1 ) {
  exit("Could not become the session leader");
};

# ... and then by forking again, to create a grandchild process

$pid = pcntl_fork();

if ($pid == -1) { exit("Could not fork child process into grandchild"); };

# Exit the child process, leaving only the grandchild beyond this point.

if ($pid) { exit(0); };

# Now to finally dissociate from the TTY and run in the background, we
# need to close our input and output streams to it. These are
# automatically defined and opened in the CLI SAPI, so will always need to
# be closed.

if (!fclose(STDIN)) { exit('Could not close STDIN'); };
if (!fclose(STDERR)) { exit('Could not close STDERR'); };
if (!fclose(STDOUT)) { exit('Could not close STDOUT'); };

# STDOUT is now closed, if you echo or print anything or interpolate HTML
# after this point, your script would crash. Likewise, any error messages
# would have nowhere to go, and any inadvertent attempts to read input
# would end badly. So instead we will recreate these three streams, but
# with sensible destinations. When we fclose the standard streams, and
# because you cannot redefine a constant, PHP simply assigns the standard
# streams to the next three new file descriptors opened (whatever they
# are called and whereever they point to). Thus the following also acts
# to protect any other streams you may open from "pollution" caused by
# accidental writes to those standard streams

$STDIN = fopen('/dev/null', 'r');
$STDOUT = fopen('/dev/null', 'w');
$STDERR = fopen('/var/log/our_error.log', 'wb');

# We are now a free-floating daemon, fully detached from our TTY!

# Now we go into our main loop, and do something useful.

# We set a variable which will allow us to escape from the loop if we
# want to shut down our daemon.

$stayInLoop = true;

while ($stayInLoop) {

    # do useful stuff here, like listening for connections,
    # monitoring things, or whatever else your daemon does.

    # When it's time to exit, set $stayInLoop = false at some point in the
    # loop and this loop will be the last. If you need to exit before the
    # loop finishes you will need to call break and do any necessary
    # tidying up. Here, we will end if it's a Tuesday, looping only once.

    if (date('l') == 'Tuesday') { $stayInLoop = false; };

    # For this example, we're going to execute a cli program called
    # notify-send to periodically pop up a notification to say hello,
    # which we looked at in Chapter 4.

    `notify-send 'Hello, The daemon is alive!'`;

    # The following line adds a "sleep" to each cycle of the loop. If we
    # didn't do this, our daemon would (try to) consume 100% of the CPU
    # time as it constantly cycles and evaluates the conditions for
    # looping. You can adjust the time it sleeps for depending on the
    # "responsiveness" required of your daemon. Giving it a break for even
    # a few 100 or 1000 milliseconds (using usleep) helps to maintain
    # overall system responsiveness.

    sleep(15); # Loop every 15 seconds
};

# We've exited our loop, so do any clean-up required here

`notify-send 'The Daemon is now finished. Bye Bye.!'`;

# And then exit

exit(0);
