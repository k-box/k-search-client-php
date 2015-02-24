<?php

/**
 * Autoload for PHP 5.2, based on the latest autoloader created by composer
 * @package Klink
 */

require_once __DIR__ . '/avvertix/jsonmapper/src/JsonMapper.php';
require_once __DIR__ . '/avvertix/jsonmapper/src/JsonMapper/Exception.php';


$classMap = require __DIR__ . '/composer/autoload_classmap.php';
//$classMap = array_values($classMap);
if ($classMap) {

	foreach ($classMap as $key => $value) {

		// echo 'Loading ' . $key . ' - ' . $value . PHP_EOL;

		if(strpos($key, 'Test')===false && strpos($key, 'test')===false && $key !== 'KlinkImageResize'){

			require_once($value);
		}
	}
    
}