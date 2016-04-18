<?php

/**
* Test the KlinkHttp Class for basic functionality
*/
class KlinkRestClientTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	    $this->rest = new KlinkRestClient("http://httpbin.org/", null, array('debug' => in_array('--debug', $_SERVER['argv'])));

	}

	public function inputNoCorrectClass()
	{
		return array(
		  array(array()),
		  array(null),
		  array(''),
		  array(10),
		  array('NonExistingNamespace\TotallyNonexistentClass'),
		);
	}

	public function inputUrlConstruction(){
		return array(
		  array('http://base.com', '', null, 'http://base.com/' ),
		  array('http://base.com', '', array(), 'http://base.com/' ),
		  array('http://base.com/', 'method', array(), 'http://base.com/method' ),
		  array('http://base.com/', 'method/{ID}', array( 'ID' => 5 ), 'http://base.com/method/5' ),
		  array('http://base.com/', 'method', array( 'query' => 'test' ), 'http://base.com/method?query=test' ),
		  array('http://base.com/', 'method/{VISIBILITY}/', array('query' => 'test', 'VISIBILITY' => 'public' ), 'http://base.com/method/public/?query=test' ),
		);	
	}

	public function statusResponseCheckErrors(){
		return array(
		  array('status/400', 400 ),
		  array('status/401', 401 ),
		  array('status/403', 403 ),
		  array('status/500', 500 ),
		);
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
	    $reflection = new ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

	/**
	 * @group deserialization
	 * @group http
     */
	public function testGetOnRestClient()
	{
		$result = $this->rest->get( 'ip', new TestResponse() );

		$this->assertFalse(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertInstanceOf('TestResponse', $result);

	}
	
	/**
	 * @dataProvider statusResponseCheckErrors
	 * @group http
	 * @group deserialization
	 */
	public function testGetErrorStatusCodes($url, $expectedCode)
	{
		$result = $this->rest->get( $url, new TestResponse() );

		$this->assertTrue(KlinkHelpers::is_error($result), 'What the hell');

		$this->assertEquals($expectedCode, $result->get_error_data_code(), 'message');

	}

	/**
     * @group http
	 * @group deserialization
     */
	public function testGetDeserializationError()
	{
		$result = $this->rest->get( 'get', new TestResponse() );

		$this->assertTrue(KlinkHelpers::is_error($result), 'Error expected');

		$this->assertContains(KlinkError::ERROR_DESERIALIZATION_ERROR, $result->get_error_codes(), 'Expected "deserialization_error" error');

	}

	/**
     * @group http
	 * @group deserialization
     */
	public function testPost()
	{

		$data = new TestBodyResponse('nome', 'cognome', 'indirizzo');

		$jsoned = json_encode($data);

		$result = $this->rest->post( 'post', $data, new TestBodyResponse() );

		$this->assertFalse(KlinkHelpers::is_error($result), 'Everything should work');

		$this->assertInstanceOf('TestBodyResponse', $result);

		$this->assertEquals($jsoned, $result->data, 'Expected data needs to be equal to the sended data');

	}
	
	/**
     * @group http
	 * @group deserialization
     */
	public function testPut()
	{

		$data = new TestBodyResponse('nome', 'cognome', 'indirizzo');

		$jsoned = json_encode($data);

		$result = $this->rest->put( 'put', $data, new TestBodyResponse() );

		$this->assertFalse(KlinkHelpers::is_error($result), 'Everything should work');

		$this->assertInstanceOf('TestBodyResponse', $result);

		$this->assertEquals($jsoned, $result->data, 'Expected data needs to be equal to the sended data');

	}

	/**
	 * @dataProvider inputNoCorrectClass
	 * @group http
	 * @group deserialization
	 */
	public function testClassExpectedError($testValue)
	{

		$result = $this->rest->get( 'ip', $testValue );

		$this->assertTrue(KlinkHelpers::is_error($result), 'Expected error');

		$this->assertContains(KlinkError::ERROR_CLASS_EXPECTED, $result->get_error_codes(), 'Expected "class_expected" error');

	}
	
	
	/**
     * @group http
     */
	public function testDelete()
	{
		$result = $this->rest->delete( 'delete' );
		
		$this->assertTrue($result, 'Expecting correct delete');
		
		$this->assertFalse(KlinkHelpers::is_error($result), 'Not expecting an error');

		// $this->assertContains(KlinkError::ERROR_DESERIALIZATION_ERROR, $result->get_error_codes(), 'Expected "deserialization_error" error');

	}
	
	/**
	 * This test aims at finding possible leaks in the temporary folder used for storing temporary streams needed for completing a request
	 */
	public function testTempDirWhilePostRequest(){
		
		$glob_before = glob(sys_get_temp_dir() .'/*.tmp');
		
		$this->testPost();
		
		$glob_after = glob(sys_get_temp_dir() .'/*.tmp');
		
		$this->assertEmpty(array_diff($glob_after, $glob_before), 'Temporary DIR is not empty after making a request');
		
	}

	/**
	 * @dataProvider inputUrlConstruction
	 */
	public function testConstructUrl($base, $rest, $getParams, $expected ){

		$url = $this->invokeMethod( $this->rest, '_construct_url' , array($base, $rest, $getParams) );

		$this->assertEquals($expected, $url);

	}



}