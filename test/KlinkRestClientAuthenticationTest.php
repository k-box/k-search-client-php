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
	    	"https://klink-dev0.cloudapp.net/kcore/",
	    	new KlinkAuthentication('https://klink-dev0.cloudapp.net/kcore/', 'admin@klink.org', 'admin.klink'));

	    $this->rest2 = new KlinkRestClient(
	    	"https://klink-dev0.cloudapp.net/kcore/",
	    	new KlinkAuthentication('https://klink-dev0.cloudapp.net/kcore/', 'admin@klink.org', '.klink'));

	}
	
	public function testGet()
	{
		$result = $this->rest->getCollection( 'institutions', null, new KlinkInstitutionDetails() );

		$this->assertFalse(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertNotNull( $result, 'Null');

	}

	public function testGetOnWrongAuth()
	{
		$result = $this->rest2->get( 'institutions/KLINK', new KlinkInstitutionDetails() );

		$this->assertTrue(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertEquals(401, $result->get_error_data_code(), 'message');

	}

	
}