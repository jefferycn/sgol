<?php
require_once 'autoload.php';

// define folder shortcuts
$root =  str_replace("\\", "/", dirname(__FILE__)) . '/../';

$site = array(
    'FOLDER_ROOT' => $root,
    'FOLDER_SQL' => $root . 'config/sql/',
    // use IP as the host, it will cause connection error if use localhost
    'DATABASE_DSN' => "mysql:dbname=sgol;host=127.0.0.1",
    'DATABASE_USER' => "root",
    'DATABASE_PASSWD' => "",
	'CHARSET' => 'UTF-8',
	'debug' => false,
);

$app->config($site);
