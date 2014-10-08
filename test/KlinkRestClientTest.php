<?php


use Klink\Utils\Helpers;
use Klink\Network\KlinkRestClient;
use Klink\Network\Authentication\KlinkAuthentication;

/**
* Test the KlinkHttp Class for basic functionality
*/
class KlinkRestClientTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	    $this->rest = new KlinkRestClient("http://httpbin.org/", new KlinkAuthentication());

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

		$this->assertFalse(Helpers::is_error($result), 'What the hell');

		$this->assertInstanceOf('TestResponse', $result);

	}

	public function testGetDeserializationError()
	{
		$result = $this->rest->get( 'get', new TestResponse() );

		// print_r($result);

		$this->assertTrue(Helpers::is_error($result), 'Error expected');

		$this->assertContains('deserialization_error', $result->get_error_codes(), 'Expected "deserialization_error" error');

	}

	public function testGetCollection()
	{
		// $result = $this->rest->getCollection( 'ip', 'TestResponse' );


		// // print_r($result);

		// $this->assertEquals(200, $result['response']['code'], 'Something wront happened');
		// $this->assertTrue(!empty($result['body']), 'The response body is empty');

		$this->assertTrue(true, 'GetCollection need a test');
	}

	public function testPost()
	{

		$data = new TestBodyResponse('nome', 'cognome', 'indirizzo');

		$jsoned = json_encode($data);

		// print_r(json_encode($data));

		$result = $this->rest->post( 'post', $data, new TestBodyResponse() );

		// print_r($result);

		$this->assertFalse(Helpers::is_error($result), 'Everything should work');

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

		$this->assertTrue(Helpers::is_error($result), 'Expected error');

		$this->assertContains('class_expected', $result->get_error_codes(), 'Expected "class_expected" error');

	}
}