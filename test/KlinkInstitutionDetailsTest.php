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

	public function serializedInstitutionsDataprovider(){
		return [
			[__DIR__ . '/json/institutiondetail-v2.1.json', '2.1'],
			[__DIR__ . '/json/institutiondetail-v2.2.json', '2.2'],
		];
	}

	public function addressDataprovider(){
		return [
			['', 'street', 'country', 'locality', 'postalcode', 'street postalcode locality. country'],
			['full address already in-place', 'street', 'country', 'locality', 'postalcode', 'full address already in-place'],
		];
	}


	/**
	 * @group deserialization
	 * @dataProvider serializedInstitutionsDataprovider
	 */
	public function testInstitutionDeserialization($file, $apiVersion)
	{

		$json = file_get_contents($file);

		$this->jm = new JsonMapper();
		$this->jm->bExceptionOnUndefinedProperty = true;

		$decoded = json_decode($json, false);
		
		$deserialized = $this->jm->map($decoded, new KlinkInstitutionDetails());

		$this->assertInstanceOf('KlinkInstitutionDetails', $deserialized);
		
		$this->assertEquals('CA', $deserialized->getID());
		$this->assertEquals('Camp Alatoo', $deserialized->getName());
		
		$this->assertEquals('Organization', $deserialized->getType());
		$this->assertEquals('secretary@camp.elcat.kg', $deserialized->getMail());
		$this->assertEquals('+996 312 909 703', $deserialized->getPhoneNumber());
		$this->assertEquals('http://camp.kg/wp-content/uploads/2014/02/logo_2.png', $deserialized->getThumbnail());
		$this->assertEquals('http://camp.kg', $deserialized->getUrl());
		$this->assertNotEmpty($deserialized->getJoinedDate());

		$this->assertInstanceOf('Klink_Address', $deserialized->getKlinkAddress());
		$this->assertNotEmpty($deserialized->getAddress());
		$this->assertTrue(is_string($deserialized->getAddress()));
		
	}

	/**
	 *
	 */
	public function testInstitutionCreation()
	{

		$joinedDate = KlinkHelpers::now();

		$deserialized = KlinkInstitutionDetails::create('id', 'name', 'Organization', $joinedDate);

		$this->assertInstanceOf('KlinkInstitutionDetails', $deserialized);
		
		$this->assertEquals('id', $deserialized->getID());
		$this->assertEquals('name', $deserialized->getName());
		
		$this->assertEquals('Organization', $deserialized->getType());
		$this->assertInstanceOf('DateTime', $deserialized->getJoinedDate());
		$this->assertEquals($joinedDate, $deserialized->getJoinedDate()->format(DateTime::RFC3339));
		
	}

	/**
	 * @dataProvider addressDataprovider
	 */
	public function testInstitutionAddress($address,$street,$country,$locality,$postalCode, $expected_full_address)
	{

		$deserialized = KlinkInstitutionDetails::create('id', 'name');

		$klinkAddress = new Klink_Address($address, $street, $country, $locality, $postalCode);

		$deserialized->setAddress($klinkAddress);

		$this->assertInstanceOf('KlinkInstitutionDetails', $deserialized);

		$this->assertInstanceOf('Klink_Address', $deserialized->getKlinkAddress());
		$this->assertEquals($klinkAddress, $deserialized->getKlinkAddress());
		$this->assertNotEmpty($deserialized->getAddress());
		$this->assertEquals($expected_full_address, $deserialized->getAddress());
		$this->assertEquals($expected_full_address, $klinkAddress->__toString());
		
		
	}

}