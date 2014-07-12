#!/bin/sh

/usr/local/bin/ruby /mpos/cronjobs/update_price.rb > /mpos/cronjobs/prices.inc.php
cat /mpos/cronjobs/prices.inc.php
