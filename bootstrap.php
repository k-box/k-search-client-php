<?php
/**
 * Bootstrap file
 *
 * Include this file to load all the Klink Adapter Boilerplate Classes
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
	define( '__DIR__' , dirname(__FILE__ ) . '/vendor' );
}

// use a plain old loader for php 5.2 while use composer loader for the new PHP versions

if(defined('KLINK_COMPATIBILITY_MODE') && KLINK_COMPATIBILITY_MODE === true){
	require_once('vendor/php52_autoload.php');
}
else {
	require_once('vendor/autoload.php');
}
