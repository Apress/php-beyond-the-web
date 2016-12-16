<?php

# Execute zenity using backticks. Zenity returns the users input as text
# which we collect into a string variable. Lets use it to pop up a
# calendar, then tell the user what day of the week it is.

$day = `zenity --calendar --text="Choose a day" --date-format="%d %b %Y"`;

if ($day) {
  echo('The date chosen is a '.date('l', strtotime($day)).".\n");
};

# Now we'll show a file selector and then show an "info" dialog to tell
# the user the size of the file selected.

$filename = trim(`zenity --file-selection`);

if (file_exists($filename)) {

$command = 'zenity --info --text "The size of the file chosen is  '.
  filesize($filename).' bytes."';

`$command`;

};
