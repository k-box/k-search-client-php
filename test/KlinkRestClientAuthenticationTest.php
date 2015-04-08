<?php

/**
* Test the KlinkRestClient support for the authentication
*/
class KlinkRestClientAuthenticationTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	    $this->rest = new KlinkRestClient(
	    	CORE_URL,
	    	new KlinkAuthentication(CORE_URL, CORE_USER, CORE_PASS));

	    $this->rest2 = new KlinkRestClient(
	    	CORE_URL,
	    	new KlinkAuthentication(CORE_URL, CORE_USER, '.klink'));

	}
	
	public function testGet()
	{
		$result = $this->rest->getCollection( 'institutions', null, new KlinkInstitutionDetails() );

		$this->assertFalse(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertNotNull( $result, 'Null');

	}

	public function testGetOnWrongAuth()
	{
		$result = $this->rest2->get( 'institutions/' . INSTITUION_ID, new KlinkInstitutionDetails() );

		$this->assertTrue(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertEquals(401, $result->get_error_data_code(), 'message');

	}

	
}