<?php

# Get one line of input from STDIN

echo ('Please Type Something In : >');

$line1 = fgets(STDIN);

echo ('**** Line 1 : '.$line1." ****\n\n");

# Get one line of input, without the newline character

echo ('Please Type Something Else In : >');

$line2 = trim(fgets(STDIN));

echo ('**** Line 2 : '.$line2." ****\n\n");

# Write an array out to STDOUT in CSV format.
# First, create an array of arrays...

$records[] = ['User', 'Full Name', 'Gender'];
$records[] = ['Rob', 'Robert Aley', 'M'];
$records[] = ['Ada', 'Augusta Ada King, Countess of Lovelace', 'F'];
$records[] = ['Grete', 'Grete Hermann', 'F'];

echo ("The following is your Data in CSV format :\n\n");

# ...then convert each array to CSV on the fly as we write it out

foreach ($records as $record) {

  fputcsv(STDOUT, $record);

};

echo ("\n\nEnd of your CSV data\n");

# Pause until the user enters something starting with a number

echo ('Please type one or more numbers : >');

while (! fscanf(STDIN, "%d\n", $your_number) ) {

  echo ("No numbers found :>");

};

echo ("Your number was $your_number\n\n");

# Send the text of a web page to STDOUT

echo ("Press enter for some interwebs :\n\n");

fread(STDIN, 1); # fread blocks until enter pressed

fwrite(STDOUT, strip_tags( file_get_contents('http://www.cam.ac.uk') ) );

# Send an error message to STDERR. You can just fwrite(STDERR,...
# if you want, or you can use the error_log function, which uses the
# defined error handling routine. By default for the CLI SAPI this is
# printing to STDERR.

error_log('System ran out of beer. ABORT. ABORT.', 4);
