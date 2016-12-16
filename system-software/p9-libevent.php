<?php

# Before we start doing any actual work, we first define a series of
# functions that will provide responses to our "events"; in this case
# http requests. The real magic of libevent occurs at the end of this
# script.

function techInfo($req) {

# This is our first response function. The object $req passed in is
# the current http request/connection.

# First we will set a "Content-Type" output header to tell the web
# browser to expect plain text rather than HTML. If we were outputting
# HTML we would still need to send a header, but the event library
# does this for us if we don't add one ourselves.

  $req->addHeader ( 'Content-Type' , "text/plain; charset=ISO-8859-1",
    EventHttpRequest::OUTPUT_HEADER );

# Next we'll gather some information about the request, and format
# it into a string to send back to the web browser.

  $replyText .= 'Command : ' . $req->getCommand() . "\n";

  $replyText .= 'Host : ' . $req->getHost() . "\n";

  $replyText .= 'Input Headers : ' .
             var_export($req->getInputHeaders(),true) . "\n";

  $replyText .= 'Output Headers : ' .
    var_export($req->getOutputHeaders(),true) . "\n";

  $replyText .= 'URI : ' . $req->getUri() . "\n";

# To send a reply back, we create an "EventBuffer" containing the
# reply contents, in our case the $replyText above

  $reply = new EventBuffer;

  $reply->add($replyText);

# Finally we send our EventBuffer to the browser, with an HTTP
# status of 200-OK to confirm everything happened correctly.

  $req->sendReply(200, "OK", $reply);

};

function closeServer($req) {

# Our next function allows the visitor to shut down the server by
# simply visiting a URL. We'll send them a message before we shut down
# to let them know.

  $reply = new EventBuffer;

  $reply->add("Ok 1337 haxor, you've killed the server...");

  $req->sendReply(200, 'OK', $reply);

# We then call the exit method of the event base, to exit the event
# loop, which we'll look at towards the end of the program.

  global $base;

  $base->exit();

};

function notFound($req) {

# This function handles the case where we can't find a resource

  $req->sendError(404, 'Does not appear to be here. Sorry.');

};

function cat($req) {

# This function is one of the most important on the internet. It
# returns a picture of a cat. You will need a cat picture named
# cat.jpg in the same directory for this to work, but that shouldn't
# be too difficult to arrange...

# As we're returning a binary image file, we need to set the
# appropriate mime-type output header.

  $req->addHeader ( 'Content-Type' , "image/jpeg" ,
    EventHttpRequest::OUTPUT_HEADER );

# Get the contents of the image file ....

  $cat = file_get_contents('cat.jpg');

  # and add them to a new EventBuffer ...

  $reply = new EventBuffer;

  $reply->add($cat);

# finally delivery the cat to an appreciative audience ....

  $req->sendReply(200, "OK", $reply);

};

function genericHandler($req) {

# This function will handle any requests that the previous functions
# haven't. We'll use the opportunity to serve up an HTML page with a
# title and a picture of a cat. The <img> tag will cause the browser
# to make a second call, which will be routed to the cat() function
# above to deliver the image file.

  $replyText = '<html><head><title>'.$req->getUri().'</title></head>';
  $replyText .= '<body><h1>Picture of cat</h1><br>';
  $replyText .= '<img src="/images/cat.jpg">';
  $replyText .= '</body></html>';

  $reply = new EventBuffer;

  $reply->add($replyText);

  $req->sendReply(200, "OK", $reply);

};

# Now we've defined all of our functions for delivering content, we need
# to actually set up our server.

# First we create an "EventBase", which is libevent's vehicle for holding
# and polling a set of events.

$base = new EventBase();

# Then we add an EventHttp object to the base, which is the Event
# extension's helper for HTTP connections/events.

$http = new EventHttp($base);

# We'll choose to respond to just GET and POST HTTP requests

$http->setAllowedMethods(
  EventHttpRequest::CMD_GET | EventHttpRequest::CMD_POST);

# Next we'll tie the functions we created above to specific URIs using
# function callbacks.

$http->setCallback("/info", "techInfo");
$http->setCallback("/close", "closeServer");
$http->setCallback("/notfound", "notFound");
$http->setCallback("/images/cat.jpg", "cat");

# Finally we'll add a default function callback to handle all other URIs.
# You could, in fact, just specify this default handler and not those
# above, and then handle URIs as you wish from inside this function using
# it as a router function.

$http->setDefaultCallback("genericHandler");

# We'll bind our script to an address and port to enable it to listen for
# connections

$http->bind("0.0.0.0", 12345);

# Then we start our event loop using the loop() function of our base. Our
# script will remain in this loop indefinitely, servicing http requests
# with the functions above, until we exit it by killing the script or,
# more ideally, calling $base->exit() as we do in the closeServer()
# function above.

$base->loop();
