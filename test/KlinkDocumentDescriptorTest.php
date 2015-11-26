<?php

/**
* Test the KlinkDocumentDescriptor Class for basic functionality
*/
class KlinkDocumentDescriptorTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
	}

	

	public function testDocumentGroupsAddAndRemove()
	{

		$doc = new KlinkDocumentDescriptor();

		$this->assertEmpty($doc->getDocumentGroups());

		$doc->addDocumentGroup(0, 1);

		$this->assertNotEmpty($doc->getDocumentGroups());

		$this->assertCount(1, $doc->getDocumentGroups());

		$first = $doc->getDocumentGroups();

		$this->assertEquals('0:1', $first[0]);

		$doc->addDocumentGroup(5, 2);

		$this->assertCount(2, $doc->getDocumentGroups());

		$doc->removeDocumentGroup(0, 1);

		$this->assertCount(1, $doc->getDocumentGroups());

		$first = $doc->getDocumentGroups();

		$this->assertEquals('5:2', $first[0]);

	}

	public function testTitleAliasAddAndRemove()
	{

		$doc = new KlinkDocumentDescriptor();

		$this->assertEmpty($doc->getTitleAliases());

		$doc->addTitleAlias('title');

		$this->assertNotEmpty($doc->getTitleAliases());

		$this->assertCount(1, $doc->getTitleAliases());

		$first = $doc->getTitleAliases();

		$this->assertEquals('title', $first[0]);


		$doc->addTitleAlias('second title');

		$this->assertCount(2, $doc->getTitleAliases());

		$doc->removeTitleAlias('title');

		$this->assertCount(1, $doc->getTitleAliases());

		$first = $doc->getTitleAliases();

		$this->assertEquals('second title', $first[0]);

	}

	/**
	 * @group deserialization
	 */
	public function testDeserialization()
	{

		$json = file_get_contents(__DIR__ . '/json/documentdescriptor.json');

		$this->jm = new JsonMapper();
		$this->jm->bExceptionOnUndefinedProperty = true;

		$decoded = json_decode($json, false);
		
		$deserialized = $this->jm->map($decoded, new KlinkDocumentDescriptor());

		$this->assertInstanceOf('KlinkDocumentDescriptor', $deserialized);
		
		$this->assertEquals('48f6f2', $deserialized->getLocalDocumentID());
		$this->assertEquals('public', $deserialized->getVisibility());
		$this->assertEquals('48f6f26b4aa5b2c6c0ce54082ab4366dd6e6eb6af4d2a6ff85659e2afb96120aa9b9ba28ed32b5103f62136474a4d6a5c4546bad4560294aa924dedb754bc7b1', $deserialized->getHash());
		$this->assertEquals('SeedInfo_48.pdf', $deserialized->getTitle());
		$this->assertEquals('http://somesite.com/document/SeedInfo_48.pdf', $deserialized->getDocumentURI());
		$this->assertEquals(array("Dushanbe","Kyrgyzstan","Europe","Yuzhniy Ferganskiy Kanal Imeni Andreyeva"), $deserialized->getLocationsString());
		
	}

	/**
	 * @group deserialization
	 */
	public function testGeoJsonDeserialization()
	{

		$json = file_get_contents(__DIR__ . '/json/documentdescriptor.json');

		$this->jm = new JsonMapper();
		$this->jm->bExceptionOnUndefinedProperty = true;

		$decoded = json_decode($json, false);

		$deserialized = $this->jm->map($decoded, new KlinkDocumentDescriptor());

		$this->assertInstanceOf('KlinkDocumentDescriptor', $deserialized);

		$location_strings = $deserialized->getLocationsString();

		$locations = $deserialized->getLocations();

		$this->assertEquals(4, count($location_strings), 'Unexpected number of location strings');

		$this->assertEquals(array(
			"Dushanbe",
		    "Kyrgyzstan",
		    "Europe",
      		"Yuzhniy Ferganskiy Kanal Imeni Andreyeva"), $location_strings);


		$this->assertEquals(count($location_strings), count($locations));

		$this->assertContainsOnlyInstancesOf('KlinkGeoJsonFeature', $locations);

		$first = $locations[0];

		$this->assertEquals('Feature', $first->getType());

		$this->assertInstanceOf('KlinkGeoJsonGeometry', $first->getGeometry());

		if($first->getGeometry()->getType() === KlinkGeoJsonGeometry::TYPE_POINT){
			$this->assertEquals(2, count($first->getGeometry()->getCoordinates()), 'Point geometry should have directly the coordinates in a single array');
		}
		else {
			$this->assertEquals(1, count($first->getGeometry()->getCoordinates()));	
		}		

		$this->assertEquals(array(
		    'name' => 'Dushanbe',
		    'geonameID' => '8406256',
		    'countryCode' => 'KG'), $first->getProperties());


	}
}