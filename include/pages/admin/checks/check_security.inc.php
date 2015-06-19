<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

// check if password salts are sane
if (strlen($config['SALT']) < 24 || strlen($config['SALTY']) < 24 || $config['SALT'] == 'PLEASEMAKEMESOMETHINGRANDOM' || $config['SALTY'] == 'THISSHOULDALSOBERRAANNDDOOM') {
  $newerror = array();
  $newerror['name'] = "Password Salts";
  $newerror['level'] = 2;
  $newerror['extdesc'] = "Salts are important because they add a random element and 'padding' to passwords and other hashed strings. They should be changed from the default and should not be too short for increased security.";
  if ($config['SALT'] == 'PLEASEMAKEMESOMETHINGRANDOM' || $config['SALTY'] == 'THISSHOULDALSOBERRAANNDDOOM') {
    $newerror['description'] = "You absolutely <u>SHOULD NOT leave your SALT or SALTY default</u> changing them will require registering again.";
  } else {
    $newerror['description'] = "SALT or SALTY is too short, they should be more than 24 characters and changing them will require registering again.</p>";
  }
  $newerror['configvalue'] = "SALT";
  $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki/Config-Setup#wiki-defines--salts";
  $error[] = $newerror;
  $newerror = null;
}

// check if htaccess exists
if (!file_exists(BASEPATH.".htaccess")) {
  $newerror = array();
  $newerror['name'] = ".htaccess";
  $newerror['level'] = 2;
  $newerror['extdesc'] = ".htaccess files let you control who/how files are accessed for Apache. If you're using Apache for MPOS, you should be using .htaccess.";
  $htaccess_link = "<a href='https://github.com/MPOS/php-mpos/blob/next/public/.htaccess'>.htaccess</a>";
  $newerror['description'] = "You don't seem to have a .htaccess in your public folder, if you're using Apache set it up: $htaccess_link";
  $newerror['configvalue'] = ".htaccess";
  $newerror['helplink'] = "https://github.com/MPOS/php-mpos/wiki";
  $error[] = $newerror;
  $newerror = null;
}
