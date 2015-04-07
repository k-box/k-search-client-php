<?php
/**
 * Bootstrap file
 *
 * Include this file to load all the Klink Adapter Boilerplate Classes.
 *
 *
 * This file can be at the same level of the composer autoload file or at the parent level.
 *
 * You need to have a composer `vendor` directory
 * 
 **/


// DO NOT EDIT AFTER THIS LINE ---------------------------------------

if( defined( 'KLINKADAPTER_DEBUG' ) && KLINKADAPTER_DEBUG ){

	ini_set('display_errors',1);
	error_reporting(E_ALL);

}

if( !defined( 'KLINKADAPTER_DEBUG' ) ){
	define( 'KLINKADAPTER_DEBUG', false );
}

// Limit the differences between php 5.2 constants and php 5.3 new ones

if(!defined('PHP_VERSION')){
	define('KLINK_PHP_RUNNING_VERSION', phpversion());
}
else {
	define('KLINK_PHP_RUNNING_VERSION', PHP_VERSION);
}

// echo 'Klink is running on ' . KLINK_PHP_RUNNING_VERSION. PHP_EOL;

if(strpos(KLINK_PHP_RUNNING_VERSION, '5.2') !== false){
	define( 'KLINK_COMPATIBILITY_MODE', true);
}

if( !defined( '__DIR__' ) ) {
	define( 'KLINK_DIR' , dirname(__FILE__ ) );
}
else {
	define( 'KLINK_DIR' , __DIR__ );
}

if(basename(KLINK_DIR) === 'vendor'){
	define('KLINK_AUTOLOAD_FOLDER', KLINK_DIR);
}
else {
	define('KLINK_AUTOLOAD_FOLDER', KLINK_DIR . '/vendor');

	if(!is_dir(KLINK_AUTOLOAD_FOLDER)){
		throw new Exception("Cannot find Composer autoloader directory. (expected in: " . KLINK_AUTOLOAD_FOLDER . ")", 1);
	}
}




// use a plain old loader for php 5.2 while use composer loader for the new PHP versions

if(!defined( '__DIR__' ) || (defined('KLINK_COMPATIBILITY_MODE') && KLINK_COMPATIBILITY_MODE === true)){
	
	require_once KLINK_AUTOLOAD_FOLDER . '/avvertix/jsonmapper/src/JsonMapper.php';
	require_once KLINK_AUTOLOAD_FOLDER . '/avvertix/jsonmapper/src/JsonMapper/Exception.php';

	$classMap = require KLINK_AUTOLOAD_FOLDER . '/composer/autoload_classmap.php';
	
	if ($classMap) {

		foreach ($classMap as $key => $value) {

			if(strpos($key, 'Test')===false && strpos($key, 'test')===false && $key !== 'KlinkImageResize'){

				require_once($value);
			}
		}
	    
	}

}
else {
	require_once( KLINK_AUTOLOAD_FOLDER . '/autoload.php');
}
