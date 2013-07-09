Description
===========

mmcFE-ng is a web frontend for Pooled LTC Mining. A pool using this interface is running at http://pool.grewe.ca

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

Donations
=========

I was hoping to keep this out of the README but apparently people remove or change the LTC address
at the bottom of the page. For those of you finding my project and are willing to appreciate the work
with some hard earned LTC feel free to donate to my LTC address: `Lge95QR2frp9y1wJufjUPCycVsg5gLJPW8`

Donors
======

These people have supported this project with a donation:

* [obigal](https://github.com/obigal)
* [vias](https://github.com/vias79)
* [WKNiGHT](https://github.com/WKNiGHT-)
* [ZC](https://github.com/zccopwrx)
* Nutnut

Pools running mmcfe-ng
======================

You can find a list of active pools [here](POOLS.md).

Requirements
============

This setup has been tested on Ubuntu 12.04, Ubuntu 13.04 and CentOS.
It should also work on any related distribution (RHEL, Debian).
For support on how to get `litecoind` or `pushpoold` to work, please ask
in the appropriate forums.

Be aware that `mmcfe-ng` is **only** for pooled mining. Solo Mining is not
supported. They will never match an upstream share, solo miners do not create
any shares, only blocks. Expect weird behavior if trying to mix them. See #299
for full information.

* Apache2
 * libapache2-mod-php5
* PHP 5.4+
 * php5-mysqlnd
 * php5-memcached
 * php5-curl
* MySQL Server
 * mysql-server
* memcached
* pushpoold
* litecoind

Features
========

The following feature have been implemented so far:

* Mobile WebUI
* Reward Systems
 * Propotional
 * PPS
 * PPLNS **NEW**
* Use of memcache for statistics instead of a cronjob
* Web User accounts
 * Re-Captcha protected registration form
* Worker accounts
 * Worker activity (live, past 10 minutes)
 * Worker hashrates (live, past 10 minutes)
* Pool statistics
* Minimal Block statistics
* Pool donations
* Pool fees
* Manual payout
* Auto payout
* Transaction list (confirmed and unconfirmed)
* Admin Panel
 * User Listing including statistics
 * Wallet information
 * News Posts **NEW**
* Notification system
 * IDLE Workers
 * New blocks found in pool
 * Auto Payout
 * Manual Payout
* Support for various Scrypt based coins via config
 * MNC
 * LTC
 * ...

Installation
============

Please take a look at the [Quick Start Guide](https://github.com/TheSerapher/php-mmcfe-ng/wiki/Quick-Start-Guide). This will give you
an idea how to setup `mmcfe-ng`.

Contributing
============

You can contribute to this project in different ways:

* Report outstanding issues and bugs by creating an [Issue][1]
* Suggest feature enhancements also via [Issues][1]
* Fork the project, create a branch and file a pull request to improve the code itself

Contact
=======

You can find me on Freenode.net, #mmcfe-ng.

License and Author
==================

Copyright 2012, Sebastian Grewe

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.


  [1]: https://github.com/TheSerapher/php-mmcfe-ng/issues "Issue"
