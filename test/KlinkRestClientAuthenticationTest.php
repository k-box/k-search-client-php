<?php

/**
* Test the KlinkHttp Class for basic functionality
*/
class KlinkRestClientAuthenticationTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	    $this->rest = new KlinkRestClient(
	    	"http://klink-experim.cloudapp.net:14000/kcore/", 
	    	new KlinkAuthentication('http://klink-experim.cloudapp.net:14000/kcore/', 'testUser', 'testPass')); //

	    //$this->testendpoint = "http://httpbin.org/";
	}
	
	public function testGet()
	{
		$result = $this->rest->get( 'institution', new KlinkInstitutionDetails() );

		print_r($result);

		$this->assertFalse(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertInstanceOf('KlinkInstitutionDetails', $result);

	}


	// public function testPost()
	// {

	// 	$data = new TestBodyResponse('nome', 'cognome', 'indirizzo');

	// 	$jsoned = json_encode($data);

	// 	// print_r(json_encode($data));

	// 	$result = $this->rest->post( 'post', $data, new TestBodyResponse() );

	// 	// print_r($result);

	// 	$this->assertFalse(KlinkHelpers::is_error($result), 'Everything should work');

	// 	$this->assertInstanceOf('TestBodyResponse', $result);

	// 	$this->assertEquals($jsoned, $result->data, 'Expected data needs to be equal to the sended data');

	// 	// $this->assertEquals(200, $result['response']['code'], 'Something wront happened');
	// 	// $this->assertTrue(!empty($result['body']), 'The response body is empty');

	// 	// $this->assertEquals('application/json', $result['headers']['content-type'], 'Expected JSON response');

	// 	// $decoded = json_decode($result['body']);

	// 	// $this->assertObjectHasAttribute('key', $decoded->json, 'returned json not have the key property');

	// }

	
}