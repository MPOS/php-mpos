#!/bin/bash
#
# TCP tweaks to optimize a server for use as a pushpool server
# These setting can be made persistent over reboots by add to /etc/sysctl.conf
#
# Disclaimer:This script is meant to be used an incentive for furthur research on the part of the user
# and no guarantee is provided that using these settings will work oon your system without
# issues or even at all.
#
#	-AnnihilaT

sysctl -w net.ipv4.tcp_syncookies="1"
sysctl -w net.ipv4.tcp_max_syn_backlog="2048"
sysctl -w net.ipv4.ip_local_port_range="15000 61000"
sysctl -w net.ipv4.tcp_fin_timeout="30"
sysctl -w net.ipv4.tcp_tw_reuse="1"
sysctl -w net.ipv4.tcp_tw_recycle="1"
sysctl -w net.core.wmem_max="8388608"
sysctl -w net.core.rmem_max="8388608"
sysctl -w net.ipv4.tcp_rmem="4096 87380 8388608"
sysctl -w net.ipv4.tcp_wmem="4096 87380 8388608"
sysctl -w net.ipv4.tcp_keepalive_probes="5"
sysctl -w net.ipv4.tcp_keepalive_intvl="30"
sysctl -w net.ipv4.tcp_timestamps="0"

