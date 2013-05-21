Description
===========

mmcFE-ng is a web frontend for Pooled LTC Mining.

The web frontend layout is based on mmcFE, the original work by Greedi:
https://github.com/Greedi/mmcFE

After working a few days trying to learn to run my own pool and the
systems behind it I figured I'd look a bit deeper in the code to
understand how it works. While doing so I also migrated the existing
code over to my own framework so maintenance would be easier in the
future.

**NOTE**: This project is still under development and commits are happening on a daily basis.
I do not recommend using this for a live setup as of yet. Wait for the later Release Candidate
if you wish to run your pool with it. Testing pools are much appreciated though!

Requirements
============

This setup has been tested on Ubuntu 12.04, Ubuntu 13.04 and CentOS.
It should also work on any related distribution (RHEL, Debian).
For support on how to get `litecoind` or `pushpoold` to work, please ask
in the appropriate forums.

* Apache2
 * libapache2-mod-php5
* PHP 5.4+ (5.3 might work too)
 * php5-mysqlnd
 * php5-memcached
* MySQL Server
 * mysql-server
* memcached
* pushpoold
* litecoind

Features
========

The following feature have been implemented so far:

* Use of memcache for statistics instead of a cronjob
* Web User accounts
* Worker accounts
 * Worker activity (live, past 10 minutes)
 * Worker hashrates (live, past 10 minutes)
* Pool statistics
* Minimal Block statistics
* Pool donations
* Pool fees
* Manual payout with 0.1 LTC fee
* Auto payout with 0.1 LTC fee
* Transaction list (confirmed and unconfirmed)

Installation
============

Please ensure you fullfill the minimal installation requirements listed above
and install any missing packages or software.

Download Source
---------------

Download the (stable) master branch from Github:

```
git clone -b master git://github.com/TheSerapher/php-mmcfe-ng.git mmcfe-ng
```

Or, if you are not using git, use the ZIP file provided:

```
wget https://github.com/TheSerapher/php-mmcfe-ng/archive/master.zip
unzip master.zip
mv php-mmcfe-ng-master mmcfe-ng
```

Permissions
-----------

Please ensure your webuser (e.g. `www-data`, `apache`) has write access to
the `mmcfe-ng/public/templates/compile` folder! Otherwise compiled
templates can not be stored:

```
sudo chown www-data mmcfe-ng/public/templates/compile
```

Apache2 Configuration
---------------------

Please point your website document root to the `mmcfe-ng/public` folder
and enable auto-index for `index.php`.

Memcache
--------

Please install and start a default memcache instance. Not only would you
need one for `pushpoold` but the statistics page is storing data in
`memcache` as well to improve performance. Your memcache can be
configured in the global configuration file (see below).

Configuration
-------------

Please create the `mmcfe-ng/public/include/config/global.inc.php`
configuration from the supplied template
`mmcfe-ng/public/include/config/global.inc.dist.php`.

Pushpoold
---------

Please ensure the passwords are read from the proper table by adding this to your configuration:

```
  # database settings
  "database" : {
    "engine" : "mysql",
    "port"    : "3306",
    "name" : "mmcfeng_database_name",
    "username" : "someuser",
    "password" : "somepass",
    "sharelog" : true,
    "stmt.pwdb":"SELECT `password` FROM `workers` WHERE `username` = ?",
    "stmt.sharelog":"INSERT INTO shares (rem_host, username, our_result, upstream_result, reason, solution) VALUES (?, ?, ?, ?, ?, ?)"
  },

```

Database
========

Now that the software is ready we need to import the database.
You will find the SQL file in the `mmcfe-ng/sql` folder.
Import this file into an existing database and you should
have the proper structure ready.

TODO
====

I tried to cover most features available in mmcFE. There might be some missing still
(like graphs, some stats) but if you figure there is a core function missing please let
me know by creating an [Issue][1] marked as `Feature Request`.

Disclaimer
==========

This is a **WIP Project**. Most functionality is now added, the core
features are available and the backend cronjobs are working. I would not recommend
running this on a live pool yet. You can play around and test basic functionality but
wait for any live deployment for at least a stable Release Candidate.

  [1]: https://github.com/TheSerapher/php-mmcfe-ng/issues "Issue"
