<?php

/**
* Test the KlinkInstitutionDetails Class for basic functionality
*/
class KlinkInstitutionDetailsTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
	}


	/**
	 * @group deserialization
	 */
	public function testInstitutionDeserialization()
	{

		$json = file_get_contents(__DIR__ . '/json/institutiondetail.json');

		$this->jm = new JsonMapper();
		$this->jm->bExceptionOnUndefinedProperty = true;

		$decoded = json_decode($json, false);
		
		$deserialized = $this->jm->map($decoded, new KlinkInstitutionDetails());

		$this->assertInstanceOf('KlinkInstitutionDetails', $deserialized);
		
		$this->assertEquals('CA', $deserialized->getID());
		$this->assertEquals('Camp Alatoo', $deserialized->getName());
		
	}

}