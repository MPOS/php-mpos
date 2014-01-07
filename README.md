Description
===========

MPOS is a web based Mining Portal for various crypto currencies. A few pools using this interface are running at:

* http://ltc.pool.grewe.ca
* http://fst.pool.grewe.ca
* http://ftc.pool.grewe.ca

**NOTE**: This project is still under development and commits are happening on a daily basis.
I do not recommend using this for a live setup as of yet. Wait for the later Release Candidate
if you wish to run your pool with it. Testing pools are much appreciated though!

Donations
=========

I was hoping to keep this out of the README but apparently people remove or change the LTC address
at the bottom of the page. For those of you finding my project and are willing to appreciate the work
with some hard earned coins feel free to donate:

* LTC address: `Lge95QR2frp9y1wJufjUPCycVsg5gLJPW8`

Website Footer
==============

When you decide to use `MPOS` please be so kind and leave the footer intact. You are not the author of the software and should honor those that have worked on it. I don't mind changing the LTC donation address at the bottom, but keep in mind who really wrote this software and would deserve those ;-).

Donors
======

These people have supported this project with a donation:

* [obigal](https://github.com/obigal)
* [vias](https://github.com/vias79)
* [WKNiGHT](https://github.com/WKNiGHT-)
* [ZC](https://github.com/zccopwrx)
* Nutnut
* Caberhagen (http://litecoin-pool.ch)

Pools running MPOS
==================

You can find a list of active pools [here](POOLS.md).

Requirements
============

This setup has been tested on Ubuntu 12.04, Ubuntu 13.04 and CentOS.
It should also work on any related distribution (RHEL, Debian).

Be aware that `MPOS` is **only** for pooled mining. Solo Mining is not
supported. They will never match an upstream share, solo miners do not create
any shares, only blocks. Expect weird behavior if trying to mix them. See #299
for full information.

* 64 bit system
 * Otherwise some coins will display wrong network hashrates
* Apache2
 * libapache2-mod-php5
* PHP 5.4+
 * php5-mysqlnd
 * php5-memcached
 * php5-curl
* MySQL Server
 * mysql-server
* memcached
* stratum-mining
* litecoind

Features
========

The following feature have been implemented so far:

* Fully re-written GUI with [Smarty][2] templates
 * Full file based template support
 * **NEW** SQL based templates
* Mobile WebUI
* Scrypt, SHA256, VARDIFF Support
* Reward Systems
 * Propotional, PPS and PPLNS
* New Theme
 * Live Dashboard
 * AJAX Support
 * Overhauled API
* Web User accounts
 * Re-Captcha protected registration form
* Worker accounts
 * Worker activity
 * Worker hashrates
* Pool statistics
* Block statistics
* Pool donations, fees and block bonuses
* Manual and auto payout
* Transaction list
* Admin Panel
 * Cron Monitoring Overview
 * User Listing including statistics
 * Wallet information
 * User Transactions
 * News Posts
 * Pool Settings
 * Templates
 * Pool Workers
 * User Reports
 * Template Overwrite
* Notification system
 * IDLE Workers
 * New blocks found in pool
 * Auto Payout
 * Manual Payout
* User-to-user Invitation System
* Support for various coins via config
 * All scrypt coins
 * All sha256d coins

Installation
============

Please take a look at the [Quick Start Guide](https://github.com/TheSerapher/php-mpos/wiki/Quick-Start-Guide). This will give you an idea how to setup `MPOS`.

Customization
=============

This project was meant to allow users to easily cusomize the system and templates. Hence no upstream framework was used to keep it as simple as possible.
If you are just using the system, there will be no need to adjust anything. Things will work out of the box! But if you plan on creating
your own theme, things are pretty easy:

* Create a new theme folder in `public/templates/`
* Create a new site_assets folder in `public/site_assets`
* Create your own complete custom template or copy from an existing one
* Change your theme in the `Admin Panel` and point it to the newly created folder

The good thing with this approach: You can keep the backend code updated! Since your new theme will never conflict with existing themes, a simple git pull will
keep your installation updated. You decide which new feature you'd like to integrate on your own theme. Bugfixes to the code will work out of the box!

Other customizations are also possible but will require merging changes together. Usually users would not need to change the backend code unless they wish to work
on non-existing features in `MPOS`. For the vast majority, adjusting themes should be enough to highlight your pool from others.

In all that, I humbly ask to keep the `MPOS` author reference and Github URL intact.

Related Software
================

There are a few other projects out there that take advantage of MPOS and it's included API. Here a quick list that you can check out for yourself:

* [MPOS IRC Bot](https://github.com/WKNiGHT-/mpos-bot) written in Python, standlone bot, using the MPOS API
* [MPOS Eggdrop Module](https://github.com/iAmShorty/mpos-eggdrop-tcl) written in TCL, adding MPOS commands to this bot, using the MPOS API

Contributing
============

You can contribute to this project in different ways:

* Report outstanding issues and bugs by creating an [Issue][1]
* Suggest feature enhancements also via [Issues][1]
* Fork the project, create a branch and file a pull request to improve the code itself

Contact
=======

You can find me on Freenode.net, #MPOS.

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


  [1]: https://github.com/TheSerapher/php-mpos/issues "Issue"
  [2]: http://www.smarty.net/docs/en/ "Smarty"
