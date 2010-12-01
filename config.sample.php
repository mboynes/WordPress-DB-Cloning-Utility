<?php
# Database connection info; populate as appropriate
define('LOCAL_DB_SERVER', 'localhost');
define('LOCAL_DB_NAME', 'some_db');
define('LOCAL_DB_USER', 'myuser');
define('LOCAL_DB_PASS', 'secret');

define('WORDPRESS_DB_SERVER', 'localhost');
define('WORDPRESS_DB_NAME', 'some_other_db');
define('WORDPRESS_DB_USER', 'anotheruser');
define('WORDPRESS_DB_PASS', 'shhhhh');

# paths to mysql and mysqldump binaries
define('PATH_TO_MYSQL', '/usr/bin/mysql');
define('PATH_TO_MYSQLDUMP', '/usr/bin/mysqldump');

# Load the uFrame framekit
require_once(dirname(__FILE__).'/uFrame/init.php');
?>