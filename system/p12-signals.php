<?php

# Enable ticks every 1 `tick-able` statement

declare(ticks = 1);

# Declare a variable which will control whether our program keeps running.

$keepRunning = true;

# Output our PID (Process ID) so that we can use it in a kill command in
# another terminal to terminate the correct (this) script
# (e.g. ~$ kill 123456 )

echo("My PID is ".getmypid()." if you wanna kill me. I dare ya!\n");

# Now we create a function to handle signals when they come in.

function signalHandler($signal)
{

  # $signal contains the signal that was received
  # Always remember that this function can be called from any point in
  # the script, so be careful if relying on the state of the program

  switch ($signal) {

    case SIGINT:

        # SIGINT is sent when a user wants to interrupt the process.
        # From the terminal this is usually by pressing Ctrl-C

            echo ("No, you may NOT interrupt me. The cheek of it.\n");

            break;

    case SIGTERM:

        # Similar to SIGINT, SIGTERM is sent when a user requests
        # termination of the process, but is a slighter "stronger"
        # request.

        echo("Well, if you REALLY insist, lets go. Bye!\n");

        # set $keepRunning to false so that we exit at the start of
        # the next loop

        global $keepRunning;
        $keepRunning = false;

        break;
    };
};

# Before we start the main body of our program, we need to tie the
# handler function we just created to the signals that we want it to
# handle.

pcntl_signal(SIGINT, 'signalHandler');
pcntl_signal(SIGTERM, 'signalHandler');

# Any signals received from now will be processed by the signalHandler
# function.

# Lets now enter a loop and do some work

while ($keepRunning) {

  echo("Yawn, nothing happening...\n");
  sleep(5);

};

# If we reached this point, then we must have exited the loop.
# That means that we must have received the SIGTERM signal as
# we haven't built any other means to exit the loop!

echo("That's it, you've stopped me. I hope you're happy.\n");
