<?php

/**
* Test the KlinkHttp Class for basic functionality
*/
class KlinkDocumentUtilsTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	// date_default_timezone_set('America/Los_Angeles');

	    // $this->rest = new KlinkRestClient("http://httpbin.org/", null); //new KlinkAuthentication('http://klink-experim.cloudapp.net:14000/', 'testUser', 'testPass')

	    //$this->testendpoint = "http://httpbin.org/";
	}

	// public function inputNoCorrectClass()
	// {
	// 	return [
	// 	  [[]],
	// 	  [null],
	// 	  [''],
	// 	  [10],
	// 	  ['NonExistingNamespace\TotallyNonexistentClass']
	// 	];
	// }

	// public function inputUrlConstruction(){
	// 	return [
	// 	  ['http://base', '', null, 'http://base/' ],
	// 	  ['http://base', '', [], 'http://base/' ],
	// 	  ['http://base/', 'method', [], 'http://base/method' ],
	// 	  ['http://base/', 'method/{ID}', [ 'ID' => 5 ], 'http://base/method/5' ],
	// 	  ['http://base/', 'method', [ 'query' => 'test' ], 'http://base/method?query=test' ],
	// 	  ['http://base/', 'method/{VISIBILITY}/', ['query' => 'test', 'VISIBILITY' => 'public' ], 'http://base/method/public/?query=test' ],
	// 	];	
	// }
	
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

 

 	public function testGetExtensionFromMimeType()
 	{

 		$actual = KlinkDocumentUtils::getExtensionFromMimeType( 'application/pdf' );

 		$this->assertEquals( 'pdf', $actual);
 		
 	}


}