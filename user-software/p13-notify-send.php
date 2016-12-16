<?php

# With shell_exec…

shell_exec('notify-send -i error "Flange Error" '.
  '"An error occurred with the Flange Grommet. Flange 2.0 not found."');

# or with backticks…

$command = 'notify-send -i info "Flange Completed" '.
  '"Flange has been Grommeted. See  manual for de-grommeting info"';

`$command`;
