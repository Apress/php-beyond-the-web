<?php

# Openlog takes an "ident" e.g. ('mysoftware') that appears in the logs to
# help you quickly filter for your own software, a set of OR'd options (we
# use LOG_PERROR to also print the logs to STDERR and LOG_PID
# to include the Process ID in the logs), and a "facility" to specify what
# type of software is logging (only LOG_USER is valid in Windows, in Linux
# you can have LOG_DAEMON, LOG_AUTH and so on).

openlog('mysoftware', LOG_PERROR | LOG_PID, LOG_USER);

# We'll log a "notice", a routine notable message

syslog(LOG_NOTICE, 'Script started. All running smoothly.');

# Then when all hell breaks loose, we'll up the anti with an alert level
# message.

syslog(LOG_ALERT, 'BACON ALERT! Script has run out of bacon!. OMG.');

# Finally we can close the log as we've no more messages to log. If we've
# specified syslog in php.ini as the location for error messages, those
# will continue to be logged, only our route for logging custom messages
# is closed here.

closelog();
