#!/bin/bash

echo "Please be aware: This cron is deprecated and will be removed. Please read: https://github.com/MPOS/php-mpos/wiki/Cronjobs#setup"
sleep 2

#########################
#                       #
# Configuration Options #
#                       #
#########################
# PHP Detections, if this fails hard code it
PHP_BIN=$( which php )

# List of cruns to execute
CRONS="findblock.php proportional_payout.php pplns_payout.php pps_payout.php blockupdate.php payouts.php tickerupdate.php notifications.php statistics.php tables_cleanup.php"

# Output additional runtime information
VERBOSE="0"

# Base path for PIDFILE, (full path).
BASEPATH="/tmp"

# Subfolder for PIDFILE, so it's path will be unique in a multipool server.
# Path relative to BASEPATH.
# Eg. SUBFOLDER="LTC"
SUBFOLDER=""

################################################################
#                                                              #
# You probably don't need to change anything beyond this point #
#                                                              #
################################################################

# Mac OS detection
OS=`uname`


case "$OS" in
  Darwin) READLINK=$( which greadlink ) ;;
  *) READLINK=$( which readlink ) ;;
esac

if [[ ! -x $READLINK ]]; then
  echo "readlink not found, please install first";
  exit 1;
fi

# My own name
ME=$( basename $0 )

# Overwrite some settings via command line arguments
while getopts "hfvp:d:" opt; do
  case "$opt" in
    h|\?)
      echo "Usage: $0 [-v] [-p PHP_BINARY] [-d SUBFOLDER]";
      exit 0
      ;;
    v) VERBOSE=1 ;;
    f) PHP_OPTS="$PHP_OPTS -f";;
    p) PHP_BIN=$OPTARG ;;
    d) SUBFOLDER=$OPTARG ;;
    :)
      echo "Option -$OPTARG requires an argument." >&2
      exit 1
    ;;
  esac
done

# Path to PID file, needs to be writable by user running this
PIDFILE="${BASEPATH}/${SUBFOLDER}/${ME}.pid"
# Clean PIDFILE path
PIDFILE=$($READLINK -m "$PIDFILE")

# Create folders recursively if necessary
if ! $(mkdir -p $( dirname $PIDFILE)); then
  echo "Error creating PIDFILE path: $( dirname $PIDFILE )"
  exit 1
fi

# Find scripts path
if [[ -L $0 ]]; then
  CRONHOME=$( dirname $( $READLINK $0 ) )
else
  CRONHOME=$( dirname $0 )
fi

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
echo $PID 2>/dev/null 1> $PIDFILE || {
  echo 'Failed to create PID file, aborting';
  exit 1
}

for cron in $CRONS; do
  [[ $VERBOSE == 1 ]] && echo "Running $cron, check logfile for details"
  $PHP_BIN $cron $PHP_OPTS
done

# Remove pidfile
rm -f $PIDFILE
