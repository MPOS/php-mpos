[![Build Status](https://travis-ci.org/MPOS/php-mpos.png?branch=master)](https://travis-ci.org/MPOS/php-mpos) [![Code Climate](https://codeclimate.com/github/MPOS/php-mpos/badges/gpa.svg)](https://codeclimate.com/github/MPOS/php-mpos) [![Code Coverage](https://scrutinizer-ci.com/g/MPOS/php-mpos/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/MPOS/php-mpos/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MPOS/php-mpos/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/MPOS/php-mpos/?branch=master) master<br />
[![Build Status](https://travis-ci.org/MPOS/php-mpos.png?branch=development)](https://travis-ci.org/MPOS/php-mpos) [![Code Coverage](https://scrutinizer-ci.com/g/MPOS/php-mpos/badges/coverage.png?b=development)](https://scrutinizer-ci.com/g/MPOS/php-mpos/?branch=development) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MPOS/php-mpos/badges/quality-score.png?b=development)](https://scrutinizer-ci.com/g/MPOS/php-mpos/?branch=development)  development


Description
===========

MPOS is a web based Mining Portal for various crypto currencies. It was created by [TheSerapher](https://github.com/TheSerapher) and has hence grown quite large. Recently it was migrated into a Github Organization to make development easier. It's a community driven open source project. Support can be requested on IRC at https://webchat.freenode.net/?channels=#mpos - Be **PATIENT** ... People listed in this channel may currently be inactive but most users there have offline logging of messages. They **will** see your questions and answer if they can. Don't join, ask the question and leave. Sit around if you want answers to your questions!

Donations
=========

Donations to this project are going directly to [TheSerapher](https://github.com/TheSerapher), the original author of this project:

* BTC address: `1HuYK6WPU8o3yWCrAaADDZPRpL5QiXitfv`
* LTC address: `Lge95QR2frp9y1wJufjUPCycVsg5gLJPW8`

Website Footer
==============

When you decide to use `MPOS` please be so kind and leave the footer intact. You are not the author of the software and should honor those that have worked on it. Keeping the footer intact helps spreading the word. Leaving the donation address untouched allows miners to donate to the author.

Donors
======

These people have supported this project with a donation:

* [obigal](https://github.com/obigal)
* [vias](https://github.com/vias79)
* [WKNiGHT](https://github.com/WKNiGHT-)
* [ZC](https://github.com/zccopwrx)
* Nutnut
* Caberhagen (https://coin-mining.ch)
* Mining4All (https://www.mining4all.eu/)
* [xisi](https://github.com/xisi)
* [PCFiL](https://github.com/PCFiL)
* [rog1121](https://github.com/rog1121)(https://rapidhash.net)
* [Wow, Much Pool](http://www.wowmuchpool.com/)
* webxassDE (https://www.suchcoins.com/)

Pools running MPOS
==================

You can find a list of active pools [here](https://github.com/TheSerapher/php-mpos/wiki/Pools).

Requirements
============

This setup has been tested on Ubuntu 12.04, Ubuntu 13.04 and CentOS.
It should also work on any related distribution (RHEL, Debian).

Be aware that `MPOS` is **only** for pooled mining. Solo Mining is not
supported. They will never match an upstream share, solo miners do not create
any shares, only blocks. Expect weird behavior if trying to mix them. See #299
for full information.

* 64-bit system
 * Otherwise some coins will display wrong network hashrates
* Apache2
 * libapache2-mod-php5
* PHP 5.4+
 * php5-json
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
* VARDIFF Support
* Reward Systems
 * Propotional, PPS and PPLNS
* New Theme
 * Live Dashboard
 * AJAX Support
 * Overhauled API
 * Bootstrap
* Web User accounts
 * Re-Captcha protected registration form
* Worker accounts
 * Worker activity
 * Worker hashrates
* Pool statistics
* Block statistics
* Pool donations, bonuses, fees and block bonuses
* Manual and auto payout
* Transaction list
* Admin Panel
 * Cron Monitoring Overview
 * User Listing including statistics
 * Wallet information
 * User Transactions
 * News Posts
 * Pool Settings
 * Pool Workers
 * User Reports
* Notification system
 * IDLE Workers
 * New blocks found in pool
 * Auto Payout
 * Manual Payout
* User-to-user Invitation System
* Support for various coins via coin class and config
 * All scrypt coins
 * All sha256d coins
 * All x11 coins
 * Others may be supported by creating a custom coin class 

Installation
============

Please take a look at the [Quick Start Guide](https://github.com/TheSerapher/php-mpos/wiki/Quick-Start-Guide). This will give you an idea how to setup `MPOS`. Please be aware that the `master` branch is our currently considered stable system while `development` is used as a test bed for all upcoming changes for `master`. If you wish to run a stable, well tested system ensure you run `git checkout master`. If you decide to stick to the `development` branch with bleeding edge code and potential bugs, just `git clone` the project.

Customization
=============

This project was meant to allow users to easily customize the system and templates. Hence no upstream framework was used to keep it as simple as possible.
If you are just using the system, there will be no need to adjust anything. Things will work out of the box! But if you plan on creating
your own theme, things are pretty easy:

* Create a new theme folder in `templates/`
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

* [MPOS IRC Bot](https://github.com/WKNiGHT-/mpos-bot) written in Python, standalone bot, using the MPOS API
* [MPOS Eggdrop Module](https://github.com/iAmShorty/mpos-eggdrop-tcl) written in TCL, adding MPOS commands to this bot, using the MPOS API
* [Windows Phone Pool App](http://www.windowsphone.com/en-us/store/app/meeneminermonitor/7ec6eac7-a642-409b-96c8-57b5cfdf45cf)
* [iPhone iMPOS App](https://itunes.apple.com/us/app/impos/id742179239?mt=8)
* [Other Windows Phone App](http://www.windowsphone.com/en-us/store/app/mining-info/952f1137-eb62-4613-8057-34576d3c9c44)

Contributing
============

You can contribute to this project in different ways:

* Report outstanding issues and bugs by creating an [Issue][1]
* Suggest feature enhancements also via [Issues][1]
* Fork the project, create a branch and file a pull request to improve the code itself

If you wish to participate contact the team on IRC: https://webchat.freenode.net/?channels=#mpos - we will point you to the proper channels!

Contact
=======

You can find the team on Freenode.net, #MPOS.

Team Members
============

Author and Project Owner: [TheSerapher](https://github.com/TheSerapher) aka Sebastian Grewe

Developers:

* [nrpatten](https://github.com/nrpatten)
* [Aim](https://github.com/fspijkerman)
* [raistlinthewiz](https://github.com/raistlinthewiz)
* [xisi](https://github.com/xisi)
* [nutnut](https://github.com/nutnut)
* [obigal](https://github.com/obigal)
* [iAmShorty](https://github.com/iAmShorty)
* [rog1121](https://github.com/rog1121)
* [neozonz](https://github.com/neozonz)

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
