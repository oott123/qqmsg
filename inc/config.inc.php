<?php
	define('DB_HOST', '127.0.0.1:3306');
	define('DB_NAME', 'qqmsg');
	define('DB_USER', 'root');
	define('DB_PASS', 'root');
	define('DB_PREF', 'qqmsg_');
	define('AUTH_PW', 'test');
	define('CACHE_DIR', '/../data/cache/');
	define('RECORD_PER_PAGE', 50);

	$table_sql = "CREATE TABLE IF NOT EXISTS `<table_name>` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `sender` tinytext NOT NULL,
  `contact` tinytext NOT NULL,
  `group` tinytext NOT NULL,
  `msg` longtext NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";