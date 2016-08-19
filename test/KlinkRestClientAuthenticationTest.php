<?php

/**
* Test the KlinkRestClient support for the authentication
*/
class KlinkRestClientAuthenticationTest extends BaseKlinkCoreClientTest
{
    /** @var KlinkRestClient */
    private $rest;

    /** @var KlinkRestClient */
    private $rest2;

	public function setUp()
	{
	  	$this->initSettings();

	    $this->rest = new KlinkRestClient(
	    	$this->corePublicUrl,
	    	new KlinkAuthentication($this->corePublicUrl, $this->coreUser, $this->corePass));

	    $this->rest2 = new KlinkRestClient(
	    	$this->corePublicUrl,
	    	new KlinkAuthentication($this->corePublicUrl, $this->coreUser, '.klink'));
	}
	
	/**
     * @group integration
     */
	public function testGet()
	{
		$result = $this->rest->getCollection( 'institutions', new KlinkInstitutionDetails() );
		$this->assertFalse(KlinkHelpers::is_error($result), 'What the hell');
		$this->assertNotNull( $result, 'Null');
	}

	/**
     * @group integration
     */
	public function testGetOnWrongAuth()
	{
		$result = $this->rest2->get( 'institutions/' . $this->institutionId, new KlinkInstitutionDetails() );
		$this->assertTrue(KlinkHelpers::is_error($result), 'What the hell');
		$this->assertEquals(401, $result->get_error_data_code(), 'message');
	}
}
