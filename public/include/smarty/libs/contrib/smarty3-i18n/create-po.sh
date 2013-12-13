#!/bin/sh
./tsmarty2c.php  ../../../../../templates/mpos > php-mpos.c
xgettext -n php-mpos.c
