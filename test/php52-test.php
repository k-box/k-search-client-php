<?php

/**
 * This is an integration test to be runned from php 5.2 because phpunit don't support php 5.2 anymore.
 * 
 * It's not a real test suite is just a way of find if some critical aspects will work on lower version of PHP as expecetd
 * */
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once('../bootstrap.php');

echo '-------------------'.PHP_EOL;

echo 'Testing K-Link Core connection to dev0'.PHP_EOL;

$config = new KlinkConfiguration( 'KLINK', 'KA', array(
		new KlinkAuthentication( 'https://klink-dev0.cloudapp.net/kcore/', 'admin@klink.org', 'f50345078ddaa64' )
	) );

$config->enableDebug();

$test_error = null;

$execution = KlinkCoreClient::test($config, $test_error);

if($execution){
	echo '   SUCCESS'. PHP_EOL;
}
else {
	var_dump($execution);

	print_r($test_error);

}

echo '-------------------'.PHP_EOL;
