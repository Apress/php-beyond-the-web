<?php

# First we will define some named constants.
# These are shell escape codes, used for formatting.
# Defining them as named constants helps to make our code more readable.

define("ESC", "\033");
define("CLEAR", ESC."[2J");
define("HOME", ESC."[0;0f");

# We will output some instructions to the user. Note that we use
# fwrite rather than echo. The aim is to write our output back to the
# shell where the user will see it. fwrite(STDOUT... writes to the
# php://stdout stream. Echo (and print) write to the php://output
# stream. Usually these are both the same thing, but they don't have to
# be. Additionally php://output is subject to the Output control &
# buffering functions (http://www.php.net/manual/en/book.outcontrol.php)
# which may or may not be desirable.

fwrite(STDOUT, "Press Enter To Begin, And Enter Again To End");

# Now we wait for the user to press enter. By default, STDIN is
# a blocking stream, which means that when we try to read from it,
# our script will stop and wait some input. Keyboard input to the shell
# is passed to our script (via fread) when the user presses Enter.

fread(STDIN,1);

# We want the program to run until the user presses enter again. This
# means that we want to periodically check for input with fread, but not
# to pause/block the program if there isn't any input. So we set STDIN to
# be non-blocking.

stream_set_blocking(STDIN, 0);

# In preparation for our output, we want to clear the terminal and draw a
# pretty frame around it. To do this we need to know how big the terminal
# window currently is. There is no built-in way to do this in PHP, so we
# call an external shell command called tput, which gives information about
# the current terminal.

$rows = intval(`tput lines`);
$cols = intval(`tput cols`);

# We now write two special escape codes to the terminal, the first
# of which (\033[2J) clears the screen, the second of which (\033[0;0f)
# puts the cursor at the top left of the screen. We've already defined
# these as the constants CLEAR and HOME at the start of the script.

fwrite(STDOUT, CLEAR.HOME);

# Now we want to draw a frame around our window. The simplest way to draw
# "graphics" (or "semigraphics/pseudographics") in the terminal is to
# use box drawing characters that are included with most fixed-width fonts
# used in terminals.

# Draw the vertical frames by moving the cursor step-by-step down each
# side. The cursor is moved with the escape code generated by
# ESC."[$rowcount;1f"

for ($rowcount = 2; $rowcount < $rows; $rowcount++) {
  fwrite(STDOUT, ESC."[$rowcount;1f"."║"); # e.g. \033[7;1f║ for line 7
  fwrite(STDOUT, ESC."[$rowcount;${cols}f"."║");
}

# Now do the same for the horizontal frames.

for ($colcount = 2; $colcount < $cols; $colcount++) {
  fwrite(STDOUT, ESC."[1;${colcount}f"."═");
  fwrite(STDOUT, ESC."[$rows;${colcount}f"."═");
}

# And finally fill in the corners.

fwrite(STDOUT, ESC."[1;1f"."╔");
fwrite(STDOUT, ESC."[1;${cols}f"."╗");
fwrite(STDOUT, ESC."[$rows;1f"."╚");
fwrite(STDOUT, ESC."[$rows;${cols}f"."╝");

# You can see the full range of box drawing characters available at
# http://en.wikipedia.org/wiki/Box-drawing_character
# They are just "text" like any other character, so you can easily copy
# and paste them into most editors.

# $p is an array [x,y] that holds the position of our cursor. We will
# initialise it to be the centre of the screen.

$p = ["x"=>intval($cols/2), "y"=>intval($rows/2)];

# Now for our first element of flow control. We need to keep the program
# running until the user provides input. The simplest way to do this is to
# use a never-ending loop using while(1). "1" always evaluates to true, so
# the while loop will never end. When we (or the user) are ready to end
# the program, we can use the "break" construct to step out of the loop
# and continue the remaining script after the end of the loop.

while (1) {

# Each time we go through the loop, we want to check if the user has
# pressed enter while we were in the last loop. Remember that STDIN is
# no longer blocking, so if there is no input the program continues
# immediately. If there is input we use break to leave the while loop.

  if (fread(STDIN,1)) { break; };

# We will step the position of the cursor, stored in $p, by a random
# amount in both the x and y axis. This makes our snake crawl!

  $p['x'] = $p['x'] + rand(-1,1);
  $p['y'] = $p['y'] + rand(-1,1);

# We check that our snake won't step onto or over the frame, to keep
# it in its box!

  if ($p['x'] > ($cols-1)) { $p['x'] = ($cols-1);};
  if ($p['y'] > ($rows-1)) { $p['y'] = ($rows-1);};
  if ($p['x'] < 2) { $p['x'] = 2;};
  if ($p['y'] < 2) { $p['y'] = 2;};

# We want a pretty trail, so we need to pick random colours for the
# foreground and background colour of our snake, that change at
# each step. Colours in the terminal are set with yet more escape
# codes, from a limited palette, specified by integers.

  $fg_color = rand(30,37);
  $bg_color = rand(40,47);

# Once chosen, we set the colours by outputting the escape codes. This
# doesn't immediately print anything, it just sets the colour of
# whatever else follows.

  fwrite(STDOUT, ESC."[${fg_color}m"); # \033[$32m sets green foreground
  fwrite(STDOUT, ESC."[${bg_color}m"); # \033[$42m sets green background

# Finally we output a segment of snake (another box drawing character)
# at the new location. It will appear with the colours we just set, at
# the location stored in $p

  fwrite(STDOUT, ESC."[${p['y']};${p['x']}f"."╬");

# Before we let the while loop start again, we need to do one more
# very important thing. We need to give your processor a rest.
# If we just continued our loop straight away, you would find your
# processor being hammered, just for our relatively simple program.
# Our snake would also consume the screen at super-speed!
# usleep pauses execution of the program, so others can use the
# processor or the processor can "rest". Every little bit helps the
# responsiveness of your machine, so even if you need your program
# to loop as fast as possible, consider even a small usleep if you can

  usleep(1000);
};

# If this line of code has been reached, it means that we have 'break'd
# from the while loop.

# To be a good citizen of the terminal, we need to clean up the screen
# before we exit. Otherwise, the cursor will remain on which-ever line
# our snake left it, and the background/foreground colours will be
# the last ones chosen for our snake segment.

# The following escape code tells the terminal to use its default colours.

fwrite(STDOUT, ESC."[0m");

# We then clear the screen and put the cursor at the top-left, as we
# did earlier.

fwrite(STDOUT, CLEAR.HOME);