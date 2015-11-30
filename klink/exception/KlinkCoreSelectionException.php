<?php


/**
* Simple Klink Exception.
* 
* @package  Klink
* @subpackage Exception
* @author   Alessio Vertemati <a.vertemati@sirisacademic.com>
*/
class KlinkCoreSelectionException extends \KlinkException
{

	function __construct($tag, $available_tags){
		
		parent::__construct(sprintf('Tag %s not found in available tags %s', $tag, implode(',', $available_tags)), 4040);

	}
	
}