#!/bin/bash

if [ -a /etc/haproxy/haproxy.$1$2$3.cfg ]
  then
    /etc/haproxy/change_haproxy.sh $1$2$3
    echo "multi-scrypt,$1,0" > /chunky/data/multiport_coin.txt
    echo "$1,$2,$3" > /mpos/public/multiport_coins.txt

    echo "switched to $1, $2, $3"
  else
    echo "/etc/haproxy/haproxy.$1$2$3.cfg does not exist"
fi


