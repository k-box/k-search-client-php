<?php


/**
* Simple Klink Exception.
* 
* @package  Klink
* @subpackage Exception
* @author   Alessio Vertemati <a.vertemati@sirisacademic.com>
*/
class KlinkException extends Exception
{

	function __construct($message = "", $code = 0, $previous = NULL ){


		if(defined('KLINK_COMPATIBILITY_MODE') && KLINK_COMPATIBILITY_MODE === true){
			print_r($previous);
			if(!is_null($previous)){
				$prev = PHP_EOL . ' original: ' . $previous->getMessage();
			}
			else {
				$prev ='';
			}
			
			parent::__construct($message . $prev, $code);
		}
		else {
			parent::__construct($message, $code, $previous);
		}

	}
	
}