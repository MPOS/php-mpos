<?php
// Put this in your MySQL Server if it's external
// sudo apt-get install libapache2-mod-php
// copy to /var/www/html/
echo json_encode(@sys_getloadavg());
