<?php
function autoload($name) {
	$root = dirname(__FILE__) . '/../';
	if(file_exists($file = $root . 'lib/'. $name . '.php')) {
		require_once $file;
		return true;
	}

	if(file_exists($file = $root . 'controllers/'. $name . '.php')) {
		require_once $file;
		return true;
	}
	
	if(file_exists($file = $root . 'model/'. $name . '.php')) {
		require_once $file;
		return true;
	}
	
	if(file_exists($file = $name . '.php')) {
		require_once $file;
		return true;
	}
	
	return false;
}

spl_autoload_register("autoload");
