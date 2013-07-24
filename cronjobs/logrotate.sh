#!/bin/bash

# Find scripts path
if [[ -L $0 ]]; then
  CRONHOME=$( dirname $( readlink $0 ) )
else
  CRONHOME=$( dirname $0 )
fi

cd $CRONHOME
logrotate etc/logrotate.conf
