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

	// public function inputNumbers()
 //  {
 //    return [
 //      [2, 2, 4],
 //      [2.5, 2.5, 5]
 //    ];
 //  }
	
 //  /**
 //   * @dataProvider inputNumbers
 //   */
 //  public function testCanAddNumbers($x, $y, $sum)
 //  {
 //    $this->assertEquals($sum, $this->calculator->add($x, $y));
 //  }

 //  /**
 //    * @expectedException InvalidArgumentException
 //    */
 //  public function testThrowsExceptionIfNonNumberIsPassed()
 //  {
 //    $calc = new Calculator;
 //    $calc->add('a', 'b');
 //  }
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

	public function testTimeoutRetry(){

		$result = $this->http->get( 'http://10.255.255.1/', array( 'timeout' => 1, 'timeout_retry' => 1) );

		print_r( $result );

		// $this->assertEquals(200, $result['response']['code'], 'Something wrong happened');
		// $this->assertNotEmpty($result['body'], 'Body empty');

		// $decoded = json_decode($result['body']);

		// $this->assertEquals('http://httpbin.org/get', $decoded->url, 'Expected url of get response');

	}

}