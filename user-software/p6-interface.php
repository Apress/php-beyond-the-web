<?php

# Create arrays to hold our command history and list of valid commands.

$history = array();

$validCommands = array();

# Define some valid commands (I imagine we're programming a command-line
# interface to killer robot here… you know, typical day-to-day stuff)

$validCommands[] = 'kill';
$validCommands[] = 'destroy';
$validCommands[] = 'obliterate';
$validCommands[] = 'history';
$validCommands[] = 'byebye';

# We want to enable tab-completion of commands, which allows the user to
# start typing a command and then press tab to have it completed, as
# happens in Bash shells and the like. We need to provide a function (via
# readline_completion_function) that will provide an array of possible
# functions names. This can be based on the $partial characters the user
# has typed or the point in the program we are at, or any other
# factors we want. In our case, we'll simply provide an array of ALL of
# the valid commands we have.

function tab_complete ($partial) {

  global $validCommands;

  return $validCommands;

};

readline_completion_function('tab_complete');

# We now enter our main program loop. Note that we don't include a usleep,
# as readline pauses our program execution while it waits for input from
# the user.

while (1) {

# We call readline with a string that forms the command prompt. In our
# case we'll put the date & time in there to show that we can change
# it each time its called. Whatever the user enters is returned. This
# one simple line implements most of the readline magic. At this stage
# the user can take advantage of tab-completion, history (use up/down
# cursor keys) and so on.

  $line = readline(date('H:i:s')." Enter command > ");

# We need to manually add commands to the history. This is used for
# the command history that the user accesses with the up/down cursor
# keys. We could choose to ignore commands (mis-typed ones or
# intermediate input, for example) if we want, although we'll add
# everything our users enter in this example.

  readline_add_history($line);

# If we want to programmatically retrieve the history, we can use a
# function called readline_list_history(). However, this is only
# available if PHP has been compiled using libreadline. In most cases,
# modern distributions compile it using the compatible libedit library
# for licensing and other reasons. So we will keep a parallel copy of
# the history in an array for programatic access.

  $history[] = $line;

# Now we decide what to do with the users input. In real life, we may
# want to trim(), strtolower() and otherwise filter the input first.

  switch ($line) {

      case "kill":

          echo "You don't want to do that.\n";

          break;

      case "destroy":

          echo "That really isn't a good idea.\n";

          break;

      case "obliterate":

          echo "Well, if we really must.\n";

          break;

      case "history":

# We will use the parallel copy of the command history that we
# created earlier to display the command history.

          $counter = 0;

          foreach($history as $command) {

            $counter++;

            echo("$counter: $command\n");

          };

          break;

      case "byebye":

# If it's time to leave, we want to break from both the switch
# statement and the while loop, so we break with a level of 2.

          break 2;

      default :

# Always remember to give feedback in the case of user error.

      echo("Sorry, command ".$line." was not recognised.\n");
  }

};

# If we reached here, outside of the while(1) loop, the user typed byebye.

echo("Bye bye, come again soon!\n");
