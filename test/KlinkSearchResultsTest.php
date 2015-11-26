<?php

/**
* Test the KlinkSearchResult Class for basic functionality
*/
class KlinkSearchResultTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
	}


	/**
	 * @group deserialization
	 */
	public function testSearchResultsDeserialization()
	{

		$json = file_get_contents(__DIR__ . '/json/searchresult.json');

		$this->jm = new JsonMapper();
		$this->jm->bExceptionOnUndefinedProperty = true;

		$decoded = json_decode($json, false);
		
		$deserialized = $this->jm->map($decoded, new KlinkSearchResult());

		$this->assertInstanceOf('KlinkSearchResult', $deserialized);
		
		$this->assertEquals('*', $deserialized->getTerms());
		$this->assertEquals(\KlinkVisibilityType::KLINK_PRIVATE, $deserialized->getVisibility());
		$this->assertEquals(58, $deserialized->getTotalResults());
		$this->assertEquals(2, $deserialized->getResultsPerPage());
		$this->assertEquals(0, $deserialized->getOffset());
		
		$this->assertContainsOnlyInstancesOf('KlinkSearchResultItem', $deserialized->getResults());
		
	}

}