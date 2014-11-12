<?php

/**
 * Autoload for PHP 5.2, based on the latest autoloader created by composer
 * @package Klink
 */


$classMap = require __DIR__ . '/composer/autoload_classmap.php';
//$classMap = array_values($classMap);
if ($classMap) {

	foreach ($classMap as $key => $value) {

		if(strpos($key, 'Test')===false && strpos($key, 'test')===false){

			require_once($value);
		}
	}
    
}