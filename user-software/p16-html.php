<?php

echo("This text will go to STDOUT (your terminal) as normal\n");

# Start buffering our output rather than sending it to STDOUT

ob_start();

# Echo and print write to the php://output stream. By default,
# php://output writes to STDOUT, which is why echo normally prints stuff
# back to the terminal. ob_start() captures the php://output stream,
# however we can still write to STDOUT directly (rather than via echo) if
# we want to tell the user what we're up to.

fwrite(STDOUT, "Starting HTML Generation...\n"); # displayed in terminal

# So we create our HTML as we would if we were "on the web"

echo('<html>');

print("<head><title>My HTML Page</title></head>\n");

?>
<body>
<h1> Intermingle Some HTML</h1>
<p>In the traditional PHP Way</p>
<p>
<a href="http://www.php.net">An Important Link</A>
<?php

echo('</body></html>');

fwrite(STDOUT, "Finished HTML Generation\n"); # displayed in terminal

# ob_get_contents() creates a string with everything buffered so far.

$ourHtml = ob_get_contents();

# We can continue with buffering if we need to, or in this case we
# end "ob_end_clean"ly. If we ob_end_flush() instead, then the contents of
# the buffer would be pushed to php://output, which after ending the
# buffering is STDOUT, which we don't want in this case.

ob_end_clean();

# Now that we've ended buffering…

echo ("This Text will go to our terminal via STDOUT\n");

# Finally, we want to save the buffered HTML to a file. Lets create
# a unique temporary file name ....

$filename = tempnam(sys_get_temp_dir(), 'my_report_').'.html';

# and write the HTML string to it.

file_put_contents($filename, $ourHtml);

# Finally, we want to open a web browser to view the HTML "report" we
# just created. Here we use the helper command "see" (available on
# most Debian based distros) to open the default viewer for the filetype
# (HTML). The command "open" achieves the same thing on other platforms.
# On Windows, you can ommit the helper command and just "execute" the
# $filename (i.e. `$filename`) and Windows will open the default viewer
# (browser) for that filetype.
#
# You can also specify a particular browser if you want,
# e.g. `firefox $filename`.

`see $filename`;
