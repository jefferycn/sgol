<?php
require_once 'autoload.php';

// define folder shortcuts
$root =  str_replace("\\", "/", dirname(__FILE__)) . '/../';

$site = array(
    'FOLDER_ROOT' => $root,
    'BASE_URI' => 'http://sgol.sinaapp.com/',
    // use IP as the host, it will cause connection error if use localhost
    'DATABASE_DSN' => "mysql:dbname=" . SAE_MYSQL_DB . ";host=" . SAE_MYSQL_HOST_M . ";port=" . SAE_MYSQL_PORT,
    'DATABASE_USER' => SAE_MYSQL_USER,
    'DATABASE_PASSWD' => SAE_MYSQL_PASS,
	'CHARSET' => 'UTF-8',
	'debug' => false,
);

$app->config($site);
