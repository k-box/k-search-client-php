<?php

/**
* Test the KlinkVisibilityType Class for basic functionality
*/
class KlinkVisibilityTypeTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidVisibilityType()
	{

		$facet_one = KlinkVisibilityType::fromString("test");


	}

	
}