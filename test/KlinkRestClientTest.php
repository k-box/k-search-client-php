<?php

/**
* Test the KlinkHttp Class for basic functionality
*/
class KlinkRestClientTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	    $this->rest = new KlinkRestClient("http://httpbin.org/", null); //new KlinkAuthentication('http://klink-experim.cloudapp.net:14000/', 'testUser', 'testPass')

	    //$this->testendpoint = "http://httpbin.org/";
	}

	public function inputNoCorrectClass()
	{
		return [
		  [[]],
		  [null],
		  [''],
		  [10],
		  ['NonExistingNamespace\TotallyNonexistentClass']
		];
	}

	public function inputUrlConstruction(){
		return [
		  ['http://base', '', null, 'http://base/' ],
		  ['http://base', '', [], 'http://base/' ],
		  ['http://base/', 'method', [], 'http://base/method' ],
		  ['http://base/', 'method/{ID}', [ 'ID' => 5 ], 'http://base/method/5' ],
		  ['http://base/', 'method', [ 'query' => 'test' ], 'http://base/method?query=test' ],
		  ['http://base/', 'method/{VISIBILITY}/', ['query' => 'test', 'VISIBILITY' => 'public' ], 'http://base/method/public/?query=test' ],
		];	
	}
	
	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

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
	public function testGet()
	{
		$result = $this->rest->get( 'ip', new TestResponse() );

		// print_r($result);

		$this->assertFalse(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertInstanceOf('TestResponse', $result);

	}

	public function testGetDeserializationError()
	{
		$result = $this->rest->get( 'get', new TestResponse() );

		// print_r($result);

		$this->assertTrue(KlinkHelpers::is_error($result), 'Error expected');

		$this->assertContains('deserialization_error', $result->get_error_codes(), 'Expected "deserialization_error" error');

	}

	// public function testGetCollection()
	// {
	// 	// $result = $this->rest->getCollection( 'ip', 'TestResponse' );


	// 	// // print_r($result);

	// 	// $this->assertEquals(200, $result['response']['code'], 'Something wront happened');
	// 	// $this->assertTrue(!empty($result['body']), 'The response body is empty');

	// 	$this->assertTrue(true, 'GetCollection need a test');
	// }

	public function testPost()
	{

		$data = new TestBodyResponse('nome', 'cognome', 'indirizzo');

		$jsoned = json_encode($data);

		// print_r(json_encode($data));

		$result = $this->rest->post( 'post', $data, new TestBodyResponse() );

		// print_r($result);

		$this->assertFalse(KlinkHelpers::is_error($result), 'Everything should work');

		$this->assertInstanceOf('TestBodyResponse', $result);

		$this->assertEquals($jsoned, $result->data, 'Expected data needs to be equal to the sended data');

		// $this->assertEquals(200, $result['response']['code'], 'Something wront happened');
		// $this->assertTrue(!empty($result['body']), 'The response body is empty');

		// $this->assertEquals('application/json', $result['headers']['content-type'], 'Expected JSON response');

		// $decoded = json_decode($result['body']);

		// $this->assertObjectHasAttribute('key', $decoded->json, 'returned json not have the key property');

	}

	/**
	 * @dataProvider inputNoCorrectClass
	 */
	public function testClassExpectedError($testValue)
	{

		$result = $this->rest->get( 'ip', $testValue );

		// $this->assertEquals(404, $result['response']['code'], 'Something wront happened');

		//class_expected

		$this->assertTrue(KlinkHelpers::is_error($result), 'Expected error');

		$this->assertContains('class_expected', $result->get_error_codes(), 'Expected "class_expected" error');

	}

	/**
	 * @dataProvider inputUrlConstruction
	 */
	public function testConstructUrl($base, $rest, $getParams, $expected ){

		$url = $this->invokeMethod( $this->rest, '_construct_url' , array($base, $rest, $getParams) );

		$this->assertEquals($expected, $url);

	}



}