<?php

//JSON_MAPPER


$map = require __DIR__ . '/composer/autoload_namespaces.php';

if($map && isset($map[''])){

	require_once( __DIR__ . '/avvertix/jsonmapper/src/JsonMapper/Exception.php' );
	require_once( __DIR__ . '/avvertix/jsonmapper/src/JsonMapper.php' );

	// $jsonMapper = $map[''];

	// var_dump($jsonMapper);
}

// foreach ($map as $namespace => $path) {
//     //require_once( $path );
    
// 	echo $namespace . PHP_EOL;
//     print_r($path);
    
// }

// echo 'PSR-4' . PHP_EOL;
// $map = require __DIR__ . '/composer/autoload_psr4.php';
// foreach ($map as $namespace => $path) {
//     //require_once( $path );
//     print_r($path);
//     echo $path . PHP_EOL;
// }


$classMap = require __DIR__ . '/composer/autoload_classmap.php';
//$classMap = array_values($classMap);
if ($classMap) {
    //require_once( $classMap );

	foreach ($classMap as $key => $value) {

		if(strpos($key, 'Test')===false){

		// echo $value . PHP_EOL;
		require_once($value);
	}
	}

    //print_r($classMap);
    
}