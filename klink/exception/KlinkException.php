<?php


/**
* Simple Klink Exception.
* 
* @package  Klink
* @subpackage Exception
* @author   Alessio Vertemati <a.vertemati@sirisacademic.com>
*/
class KlinkException extends \Exception
{

	function __construct($message = "", $code = 0, $previous = NULL ){

		parent::__construct($message, $code, $previous);

	}
	
}