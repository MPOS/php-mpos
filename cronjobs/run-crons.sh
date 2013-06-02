#!/bin/bash


#########################
#                       #
# Configuration Options #
#                       #
#########################
# PHP Detections, if this fails hard code it
PHP_BIN=$( which php )

# Path to PID file, needs to be writable by user running this
PIDFILE='/tmp/mmcfe-ng-cron.pid'

# Location of our cronjobs, assume current directory
CRONHOME='.'

# List of cruns to execute
CRONS="findblock.php proportional_payout.php blockupdate.php auto_payout.php tickerupdate.php"

# Additional arguments to pass to cronjobs
CRONARGS="-v"

# Output additional runtime information
VERBOSE="0"

################################################################
#                                                              #
# You probably don't need to change anything beyond this point #
#                                                              #
################################################################

# Change working director to CRONHOME
if ! cd $CRONHOME 2>/dev/null; then
  echo "Unable to change to working directory \$CRONHOME: $CRONHOME"
  exit 1
fi

# Confiuration checks
if [[ -z $PHP_BIN || ! -x $PHP_BIN ]]; then
  echo "Unable to locate you php binary."
  exit 1
fi

if [[ ! -e 'shared.inc.php' ]]; then
  echo "Not in cronjobs folder, please ensure \$CRONHOME is set!"
  exit 1
fi

# Our PID of this shell
PID=$$

if [[ -e $PIDFILE ]]; then
  echo "Cron seems to be running already"
  RUNPID=$( cat $PIDFILE )
  if ps fax | grep -q "^\<$RUNPID\>"; then
    echo "Process found in process table, aborting"
    exit 1
  else
    echo "Process $RUNPID not found. Plese remove $PIDFILE if process is indeed dead."
    exit 1
  fi
fi

# Write our PID file
echo $PID > $PIDFILE

for cron in $CRONS; do
  [[ $VERBOSE == 1 ]] && echo "Running $cron, see output below for details"
  $PHP_BIN $cron $CRONARGS
done
