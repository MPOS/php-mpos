#!/bin/bash

####################
# The purpose of this script is to setup a built ubuntu 10.04
# server as an auth server with next-to zero input from the user.
# This script will automagically download components
# from the ga4php site and install them locally on the machine
# it will also install all packages required for the system to work

print_usage() {
	echo "Usage: $0 dedicated|shared"
	echo "Dedicated means the website will be hosted as /"
	echo "Shared means the website will be hosted as /ga"
}

if [ "x$1" = "x" ]
then
	print_usage
	exit 0
fi

if [ "$USER" != "root" ]
then
	echo "This script must be run as root in order to function"
	exit 0
fi

case "$1" in
	"dedicated")
		echo "Installing as dedicated"
	;;
	"shared")
		echo "Installing as shared"
	;;
	*)
	print_usage
	exit 0
esac

# ok, we're ready to function, tell the user some stuff
echo "This script will now install packages required for the server to work"
echo "as well as install the auth server and start scripts into /opt/gaas and"
echo "/etc/init.d/. I will try to do this as quitely as possible"

read -p "are you sure [y/N]? " surity

if [ "x$surity" != "xy" ]
then
	echo "Bailing, must hit y if you are sure"
	exit 0
fi

# first install apt packages for apache and php
echo "Beginning install of apt-get packages"
apt-get install -y apache2 libapache2-mod-php5 php-soap php5-ldap php5-cli php5-adodb subversion > /dev/null 2>&1

if [ "$?" != "0" ]
then
	echo "There was a problem during install of apt-get packages"
	exit 1
fi

echo "Apt-get packages installed, getting auth server code"

# get the auth server code from svn
MYTMPDIR="/tmp/ga$RANDOM"
mkdir -p $MYTMPDIR
cd $MYTMPDIR
svn checkout http://ga4php.googlecode.com/svn/trunk/authserver authserver > /dev/null 2>&1
if [ "$?" != "0" ]
then
	echo "There was a problem downloading the authserver source code.. bailing"
	exit 2
fi

svn checkout http://ga4php.googlecode.com/svn/trunk/contrib contrib > /dev/null 2>&1
if [ "$?" != "0" ]
then
	echo "There was a problem downloading the contrib source code.. bailing"
	exit 2
fi

echo "Code downloaded, beginning installation"

