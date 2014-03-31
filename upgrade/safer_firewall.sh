#!/bin/sh
# Copyright 2014 Samuel Christison, May be adapted freely.
# This script will stop most; port scanning attempts, UPD Floods,
# SYN Floods, TCP Floods, Handshake Exploits, XMAS Packets,
# Smurf Attacks, ICMP Bombs, LAND attacks and RST Floods.
# You need to give this script Root privileges Before you run it.
# sudo chmod u+x 777 SecureIT.sh
# This script by default will leave open ports 80, 25, 53, 443, 22.
# This script assumes you have 1 Stratum port, And that it is port 3333
#############################################
$WEB = 80
$MAIL = 25
$DNS = 53
$SSL = 443
$SSH = 22
$STRATUM = 3333
$TCPBurstNew = 200
$TCPBurstEst = 50


echo "Lets start by Flushing your old Rules."
sleep 0.1

iptables -F

echo "Done"
sleep 0.1
echo "We need to create the Default rule and Accept LoopBack Input."
sleep 0.1

iptables -A INPUT -i lo -p all -j ACCEPT

echo "Enabling the 3 Way Hand Shake and limiting TCP Requests."
echo "Note if you have cloud flare the limit needs to be rather high or you need to make a set of separate rules for their IP range."
sleep 2

iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
sudo iptables -A INPUT -p tcp --dport $WEB -m state --state NEW -m limit --limit 50/minute --limit-burst $TCPBurstNew -j ACCEPT
sudo iptables -A INPUT -m state --state RELATED,ESTABLISHED -m limit --limit 50/second --limit-burst $TCPBurstEst -j ACCEPT

echo "Adding Protection from LAND Attacks, If these IPs look required, please stop the script and alter it."

echo "10.0.0.0/8 DROP"
sleep 1
iptables -A INPUT -s 10.0.0.0/8 -j DROP
echo "169.254.0.0/16 DROP"
sleep 1
iptables -A INPUT -s 169.254.0.0/16 -j DROP
echo "172.16.0.0/12 DROP"
sleep 1
iptables -A INPUT -s 172.16.0.0/12 -j DROP
echo "127.0.0.0/8 DROP"
sleep 1
iptables -A INPUT -s 127.0.0.0/8 -j DROP
echo "192.168.0.0/24 DROP"
sleep 1
iptables -A INPUT -s 192.168.0.0/24 -j DROP
echo "224.0.0.0/4 SOURCE DROP"
sleep 1
iptables -A INPUT -s 224.0.0.0/4 -j DROP
echo "224.0.0.0/4 DEST DROP"
sleep 1
iptables -A INPUT -d 224.0.0.0/4 -j DROP
echo "224.0.0.0/5 SOURCE DROP"
sleep 1
iptables -A INPUT -s 240.0.0.0/5 -j DROP
echo "224.0.0.0/5 DEST DROP"
sleep 1
iptables -A INPUT -d 240.0.0.0/5 -j DROP
echo "0.0.0.0/8 SOURCE DROP"
sleep 1
iptables -A INPUT -s 0.0.0.0/8 -j DROP
echo "0.0.0.0/8 DEST DROP"
sleep 1
iptables -A INPUT -d 0.0.0.0/8 -j DROP
echo "239.255.255.0/24 DROP SUBNETS"
sleep 1
iptables -A INPUT -d 239.255.255.0/24 -j DROP
echo "255.255.255.255 DROP SUBNETS"
sleep 1
iptables -A INPUT -d 255.255.255.255 -j DROP

echo "Lets stop ICMP SMURF Attacks at the Door."

iptables -A INPUT -p icmp -m icmp --icmp-type address-mask-request -j DROP
iptables -A INPUT -p icmp -m icmp --icmp-type timestamp-request -j DROP
iptables -A INPUT -p icmp -m icmp -m limit --limit 1/second -j ACCEPT

sleep 1
echo "Done\!"
echo "Next were going to drop all INVALID packets\."

iptables -A INPUT -m state --state INVALID -j DROP
iptables -A FORWARD -m state --state INVALID -j DROP
iptables -A OUTPUT -m state --state INVALID -j DROP

sleep 1
echo "Done\!"
echo "Next we drop Valid but incomplete packets."

sudo iptables -A INPUT -p tcp -m tcp --tcp-flags FIN,SYN,RST,PSH,ACK,URG NONE -j DROP 
sudo iptables -A INPUT -p tcp -m tcp --tcp-flags FIN,SYN FIN,SYN -j DROP 
sudo iptables -A INPUT -p tcp -m tcp --tcp-flags SYN,RST SYN,RST -j DROP 
sudo iptables -A INPUT -p tcp -m tcp --tcp-flags FIN,RST FIN,RST -j DROP 
sudo iptables -A INPUT -p tcp -m tcp --tcp-flags FIN,ACK FIN -j DROP 
sudo iptables -A INPUT -p tcp -m tcp --tcp-flags ACK,URG URG -j DROP

sleep 1
echo "Done\!"
echo "Now we enable RST Flood Protection MORE SMURF PROTECTION"

iptables -A INPUT -p tcp -m tcp --tcp-flags RST RST -m limit --limit 2/second --limit-burst 2 -j ACCEPT

sleep 1
echo "Done\!"
echo "Protection from Port Scans."
echo "Attacking IP will be locked for 24 hours (3600 x 24 = 86400 Seconds)"
sleep 1

iptables -A INPUT -m recent --name portscan --rcheck --seconds 86400 -j DROP
iptables -A FORWARD -m recent --name portscan --rcheck --seconds 86400 -j DROP

echo "Adjusting..."
echo "Banned IP addresses are removed from the list every 24 Hours."

iptables -A INPUT -m recent --name portscan --remove
iptables -A FORWARD -m recent --name portscan --remove

sleep 1
echo "Done\!"
echo "Creating rules to add scanners to the PortScanner list and log the attempt. Remember to set up QUOTA"

iptables -A INPUT -p tcp -m tcp --dport 139 -m recent --name portscan --set -j LOG --log-prefix "portscan:"
iptables -A INPUT -p tcp -m tcp --dport 139 -m recent --name portscan --set -j DROP

iptables -A FORWARD -p tcp -m tcp --dport 139 -m recent --name portscan --set -j LOG --log-prefix "portscan:"
iptables -A FORWARD -p tcp -m tcp --dport 139 -m recent --name portscan --set -j DROP

sleep 1
echo "Done\!"
echo "Lets block all incoming PINGS, Although they should be blocked already"

iptables -A INPUT -p icmp -m icmp --icmp-type 8 -j REJECT

sleep 1
echo "Done\!"
echo "Allow the following ports through from outside"

echo "SMTP Port $MAIL"
iptables -A INPUT -p tcp -m tcp --dport $MAIL -j ACCEPT

sleep 0.1
echo "Done\!"

echo "Web Port $WEB"
iptables -A INPUT -p tcp -m tcp --dport $WEB -j ACCEPT

sleep 0.1
echo "Done\!"

echo "DNS Port $DNS"
iptables -A INPUT -p udp -m udp --dport $DNS -j ACCEPT

sleep 1
echo "Done\!"

echo "SSL Port $SSL"
iptables -A INPUT -p tcp -m tcp --dport $SSL -j ACCEPT

sleep 1
echo "Done\!"

echo "SSH Port $SSH"
iptables -A INPUT -p tcp -m tcp --dport $SSH -j ACCEPT

sleep 1
echo "Done Opening Ports For Web Access\!"

################################################## YOUR STRATUM PORT OR PORTS ######################################
# eg. #####################iptables -A INPUT -p tcp -m tcp --dport 4545 -j ACCEPT #######

echo "Enabling Stratum Port INPUT"
sleep 0.5
iptables -A INPUT -p tcp -m tcp --dport $STRATUM -j ACCEPT

sleep 1
echo "Done\!"
################################################## YOUR STRATUM PORT OR PORTS ######################################

echo "Lastly we block ALL OTHER INPUT TRAFFIC."
iptables -A INPUT -j REJECT

sleep 1
echo "Done\!"

################# Below are for OUTPUT iptables rules #############################################
echo "NOW LETS SET UP OUTPUTS"

echo "Default Rule for OUTPUT and our LoopBack Again. We wont be limiting outgoing traffic."
iptables -A OUTPUT -o lo -j ACCEPT
iptables -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

sleep 1
echo "Done\!"

echo "Allow the following ports Access OUT from the INSIDE"

echo "SMTP Port $MAIL"
iptables -A OUTPUT -p tcp -m tcp --dport $MAIL -j ACCEPT

sleep 1
echo "Done\!"

echo "DNS Port $DNS"
iptables -A OUTPUT -p udp -m udp --dport $DNS -j ACCEPT

sleep 1
echo "Done\!"

echo "Web Port $WEB"
iptables -A OUTPUT -p tcp -m tcp --dport $WEB -j ACCEPT

sleep 1
echo "Done\!"

echo "HTTPS Port $SSL"
iptables -A OUTPUT -p tcp -m tcp --dport $SSL-j ACCEPT

sleep 1
echo "Done\!"

echo "SSH Port $SSH"
iptables -A OUTPUT -p tcp -m tcp --dport $SSH -j ACCEPT

sleep 1
echo "Done\!"

################################################## YOUR STRATUM PORT OR PORTS ######################################
# eg. #####################iptables -A OUTPUT -p tcp -m tcp --dport 4545 -j ACCEPT #######
echo "Setting up your OUTGOING Stratum Port or Ports"

iptables -A OUTPUT -p tcp -m tcp --dport $STRATUM -j ACCEPT


sleep 1
echo "Done\!"
################################################## YOUR STRATUM PORT OR PORTS ######################################

echo "Allow Outgoing PING Type ICMP Requests"

iptables -A OUTPUT -p icmp -m icmp --icmp-type 8 -j ACCEPT

sleep 1
echo "Done\!"

echo "Lastly Reject all Output traffic"

iptables -A OUTPUT -j REJECT

sleep 1
echo "Done\!"

echo "Reject Forwarding  traffic"

iptables -A FORWARD -j REJECT

sleep 1
echo "Done\!"
echo "Your Webserver is now more secure then it was 5 minutes ago"
echo "Hash On"
sleep 5
exit

