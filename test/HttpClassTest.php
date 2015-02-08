<?php


/**
* Test the KlinkHttp Class for basic functionality
*/
class HttpClassTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	    $this->http = new KlinkHttp('http://localhost/');

	    $this->testendpoint = "http://httpbin.org/";
	}

	public function testHttpGet()
	{
		$result = $this->http->get( $this->testendpoint . 'ip');


		// print_r($result);

		$this->assertFalse(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertEquals(200, $result['response']['code'], 'Something wrong happened');
		$this->assertTrue(!empty($result['body']), 'The response body is empty');
	}

	public function testHttpPost()
	{

		$data = array('key' => 'test', );


		$result = $this->http->post( $this->testendpoint . 'post', 
			array(
				'body' => json_encode($data), 
				'headers' => 'Content-Type:application/json'
			) );

		$this->assertEquals(200, $result['response']['code'], 'Something wront happened');
		$this->assertTrue(!empty($result['body']), 'The response body is empty');

		$this->assertEquals('application/json', $result['headers']['content-type'], 'Expected JSON response');

		$decoded = json_decode($result['body']);

		// print_r($result);

		$this->assertObjectHasAttribute('key', $decoded->json, 'returned json not have the key property');

	}

	public function testHttpError_NotFound()
	{

		$result = $this->http->get( $this->testendpoint . 'status/404');

		$this->assertEquals(404, $result['response']['code'], 'Something wrong happened');
		$this->assertEmpty($result['body'], 'Body not empty');

	}

	public function testHandleRedirect(){

		$result = $this->http->get( $this->testendpoint . 'redirect/2');

		$this->assertEquals(200, $result['response']['code'], 'Something wrong happened');
		$this->assertNotEmpty($result['body'], 'Body empty');

		$decoded = json_decode($result['body']);

		$this->assertEquals('http://httpbin.org/get', $decoded->url, 'Expected url of get response');

	}

	public function testConnectionRefused(){

		$result = $this->http->get( 'http://repo.klink.dyndns.ws:81' );

		$this->assertTrue(KlinkHelpers::is_error($result), 'Expecting error');

		$this->assertNotEmpty($result->get_error_message( KlinkError::ERROR_CONNECTION_REFUSED), 'Expected timeout error');

	}

	public function testTimeoutRetry(){

		$result = $this->http->get( 'http://192.10.0.2', array( 'timeout' => 1, 'timeout_retry' => 1) );

		$this->assertTrue(KlinkHelpers::is_error($result), 'Expecting error');

		$this->assertNotEmpty($result->get_error_message( KlinkError::ERROR_HTTP_REQUEST_TIMEOUT), 'Expected timeout error');

	}

}