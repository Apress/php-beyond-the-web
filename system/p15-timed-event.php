<?php

# We enable ticks for pcntl to use as before.

declare(ticks = 1);

$keepRunning = true;

# This is the function that we want to execute every 5 seconds.

function takeABreak($signal) {

   echo("\n\n ======== HAVING A BREAK, BE BACK SOON ========");

   sleep(3);

   echo("\n\n ======== BREAKS OVER, BACK TO WORK! ======== \n\n");

   # We need to request an alarm again each time.

   pcntl_alarm(5);

};

# This is a function to gracefully exit our program

function timeToGo($signal) {

   global $keepRunning;

$keepRunning = false;

};

# In a moment we will ask the system to set an alarm, but before that
# we will register the callback function that will happen when the
# alarm goes off, i.e. when the system sends us a SIGALARM signal

pcntl_signal(SIGALRM, "takeABreak", true);

# Just to show that we can handle any and all signals with more than
# one callback function, we'll also register a different handler for
# the SIGINT/SIGTERM signals

pcntl_signal(SIGINT, 'timeToGo');
pcntl_signal(SIGTERM, 'timeToGo');

# Once we have got a callback function registered, we can go ahead and
# ask the system to set an alarm for us. The alarm only works once, so
# you'll notice this call to pcntl_alarm is repeated at the end of the
# callback function to set the alarm again

pcntl_alarm(5);

# Now we enter our main work loop

while ( $keepRunning ) {

   echo('...doing work...');

   usleep(50000);

};

# If we get here, then we've had the SIGINT or SIGTERM signals

echo("\nBye Bye, see you tomorrow.\n");
