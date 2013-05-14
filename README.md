Description
===========

mmcFE-ng is a web frontend for Pooled LTC Mining.

This is based on mmcFE, the original work by Greedi:
https://github.com/Greedi/mmcFE

After working a few days trying to learn to run my own pool and the
systems behind it I figured I'd look a bit deeper in the code to
understand how it works. While doing so I also migrated the existing
code over to my own framework so maintenance would be easier in the
future.

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
`memcache` as well to improve performance.

Configuration
-------------

Please create the `mmcfe-ng/public/include/config/global.inc.php`
configuration from the supplied template
`mmcfe-ng/public/include/config/global.inc.dist.php`.

Please validate your settings, then try to access the site.
You should now be able to register a new account, setup a worker
and get started!

Disclaimer
==========

This is a *WIP Project*. Most functionality is now added, the core
features are available and the backend cronjobs are working. If you
encounter any problems related to the code please create a new [Issue] [1]

  [1]: https://github.com/TheSerapher/php-mmcfe-ng/issues "Issue"
