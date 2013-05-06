#!/bin/bash
#
# This is where all the magic happens. These scripts count shares, calculate payouts,
# calculate user hashrates, decide when we have found a block and what to do about it,
# and determine if workers are alive or dead. They are the the meat of this package.
#

## Make sure this matches the location of your
## of your php binary.
PHP_BIN="/usr/bin/php";

####################################################################################
PID=$$;
PIDFILE=/var/run/pool_update.pid

if [ -e $PIDFILE ]; then
 echo "Already running. I cannot be twice invoked.";
 exit
else
 echo $PID > $PIDFILE
 cd /sites/mmc/cronjobs/
 echo -e "\ncronjob.php\n-------------"; time $PHP_BIN cronjob.php; sleep 1;
 echo -e "\nshares.php\n-------------"; time $PHP_BIN shares.php; sleep 1;
 echo -e "\npayout.php\n-------------"; time $PHP_BIN payout.php; sleep 1;
 echo -e "\nworkers.php\n-------------"; time $PHP_BIN workers.php; sleep 1;
 echo -e "\nhashrate.php\n-------------"; time $PHP_BIN hashrate.php; sleep 1;
 echo -e "\narchive.php\n-------------"; time $PHP_BIN archive.php; sleep 1;
 $PHP_BIN tickers.php &
 rm -rf $PIDFILE
fi
