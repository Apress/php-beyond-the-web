<?php

# First we need to include the library itself which contains two primary
# files, the first contains the GPIO class and the second contains a class
# representing the RP itself, which is used by the first.

include('php-gpio/src/PhpGpio/Gpio.php');
include('php-gpio/src/PhpGpio/Pi.php');

# Next, create an GPIO object $gpio

$gpio = new PhpGpio\Gpio();

# Reserve pin 17 and set it as an input

$gpio->setup(17, "in");

# Enter a loop and wait for the doorbell to be pressed

while (1) {

  # Read the current state of pin 17

  $currently = $gpio->input(17);

  # If the switch is not pressed, the state will be 1. If the switch
  # IS pressed, the state will be 0. As the state is a "file" designed
  # to be read with a shell command like cat, it will be returned as
  # a string containing the 1 or 0 and \n. Don't just test for 0,
  # e.g. $currently == 0 as the function will return false on certain
  # errors, which would evaluate as 0.

  if ($currently === "0\n") {

  # Ding dong! Someone has pressed our doorbell. Use mplayer to
  # play an appropriate sound file. This requires amplified speakers
  # connected to either the sound jack or HDMI connector on the RP.

  shell_exec('mplayer dingdong.mp3');

  # We then continue the loop, awaiting our next visitor.

  };

  # Give the processor a chance to breath. Not too long though, or
  # we may miss a quick press of the doorbell switch.

  usleep(1000);
};
