Description
===========

This is a simple PHP framework that I used in a few projects of mine. I
was getting annoyed how long it always took to get the basic setup of a
PHP website done (classes, pages, actions) so I wrote this framework to
help speed up my development process without using one of the big
frameworks out there.

Requirements
============

*No other software required*

Overview
========

Here a quick overview on how the framework works.

Classes
-------

* [[include/classes/]]

For classes used in your projects, a debug class is already included.

Pages
-----

* [include/pages](include/pages)/`pagename`.inc.php

For pages that are called by `index.php?page=<pagename>`. *pagename.inc.php*
must exist and will be included by the index.php file when required.

Actions
-------

* [include/pages](include/pages)/`pagename`/`action`.inc.php

For each page you can create a subdirectory that will be searched for callable
actions for a page: `index.php?page=<pagename>&action=<action>`. *action.inc.php*
must exist in the directory and will be included by the `index.php` file.

Default Page
------------

Currently the framework always defaults to `home`. This still needs to be made
configurable at some point but technical difficulties made this impossible as
a short term solution.

Default Action
--------------

The default action for all pages is unset which means by default the page
include file is loaded. Once an action is defined the page include file
is skipped and the action loaded instead.

Smarty Defaults
---------------

Smarty defaults are configured in the [include/smarty.inc.php](include/smarty.inc.php) file. Change
if need be but usually the defaults should work for you.

Themes
------

As it is the framework has simple theming support. If you wish to add a new
theme create a subdirectory in templates and define that new theme in the
global.inc.php configuration file. You will also need to place any images
and the CSS in the site_assets directory (which is always public accessible).

index.php
---------

The index file is taking care of including all your coded pages and actions.
Please prefix each class, function, action and page with a

    if (!defined('SECURITY'))
      die('Hacking attempt');

This check ensures all files are called through the `index.php` and not directly.

Setup
=====

To use this framework simply upload everything to your webserver and change
the permissions on the

`templates/compile`
`templates/cache`

to `777` (read/write by everyone) so the Webserver can create the compiled
templates and cache files (if configured, disabled by default).

Once installed just call the `index.php` and you should see the demo page.

Debugger
========

This framework comes with a debugger. Examples on how to use them are given
inside the example pages. You can enable the debugger by setting the defined
DEBUG variable to your debugging verbosity level. Locate the file in:

[include/config/global.inc.php](include/config/global.inc.php)

You can also add any other defines and configuration variables you use
throughout your scripts in this file. It will be included by `index.php`.

License and Author
==================

Author:: Sebastian Grewe (<sebastian.grewe@gmail.com>) 

Copyright:: 2013, Sebastian Grewe

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
