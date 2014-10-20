<?php

/**
 * This is an integration test to be runned from php 5.2 because phpunit don't support php 5.2 anymore.
 * 
 * It's not a real test suite is just a way of find if some critical aspects will work on lower version of PHP as expecetd
 * */
ini_set('display_errors',1);
error_reporting(E_ALL);



if(!defined('PHP_VERSION')){
	define('KLINK_PHP_RUNNING_VERSION', phpversion());
}
else {
	define('KLINK_PHP_RUNNING_VERSION', PHP_VERSION);
}

echo 'Klink is running on ' . KLINK_PHP_RUNNING_VERSION. PHP_EOL;

if(strpos(KLINK_PHP_RUNNING_VERSION, '5.2') !== false){
	define( 'KLINK_COMPATIBILITY_MODE', true);
	echo "OLD PHP" . PHP_EOL;
}


if( !defined( '__DIR__' ) ) {
	define( '__DIR__' , dirname(__FILE__ ) . '/vendor' );
}

echo '-------------------'.PHP_EOL;

echo __DIR__.PHP_EOL;
echo '-------------------'.PHP_EOL;



if(defined('KLINK_COMPATIBILITY_MODE') && KLINK_COMPATIBILITY_MODE === true){
	require_once('vendor/php52_autoload.php');
}
else {
	require_once('vendor/autoload.php');
}


echo PHP_EOL;
echo 'Hello ' . hash( 'sha512', 'Hello' )  . PHP_EOL;
echo PHP_EOL;


$data = new Klink_Address();
$data->street = "STreet";
$data->city = "City";

echo PHP_EOL;
$jsoned = json_encode($data);
echo $jsoned;
echo PHP_EOL;

$jm = new JsonMapper();
$jm->bExceptionOnUndefinedProperty = true;

$deserialized = $jm->map( json_decode( $jsoned ), new Klink_Address() );

var_dump($deserialized);

print_r($deserialized);

