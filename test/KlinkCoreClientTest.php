<?php

/**
* Test the KlinCoreClient Class for basic functionality
*/
class KlinkCoreClientTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');

	  	error_reporting(E_ALL & E_STRICT);
		  
		$config = new KlinkConfiguration( 'KLINK', 'KA', array(
				new KlinkAuthentication( 'https://public.klink.asia/', 'someuser', 'somepassword', \KlinkVisibilityType::KLINK_PUBLIC ),
				new KlinkAuthentication( 'https://core.klink.asia/', 'someuser', 'somepassword', \KlinkVisibilityType::KLINK_PRIVATE ),
			) );

		if(in_array('--debug', $_SERVER['argv'])){
			$config->enableDebug();	
		}

		$this->core = new KlinkCoreClient($config);

	}




	/**
	 * Test the selection of a core connection based on the core tag
	 */
	public function testGetPrivateTaggedCore()
	{

		$result = $this->invokeMethod($this->core, '_get_connection', array('private'));

		$this->assertNotNull($result, 'result must exists');
		
		$this->assertInstanceOf('KlinkRestClient', $result);
		
		// var_dump($result);

		$result = $this->invokeMethod($this->core, '_get_connection', array('public'));

		$this->assertNotNull($result, 'result must exists');
		
		$this->assertInstanceOf('KlinkRestClient', $result);

	}
	
	/**
	 * @expectedException \KlinkCoreSelectionException
	 */
	public function testGetTaggedCoreExceptionExpected()
	{

		$result = $this->invokeMethod($this->core, '_get_connection', array('foo'));

	}
	
	
	public function testCoreSelectionBackwardCompatibility(){
		
		$config = new KlinkConfiguration( 'KLINK', 'KA', array(
				new KlinkAuthentication( 'https://core.klink.asia/', 'someuser', 'somepassword', \KlinkVisibilityType::KLINK_PRIVATE )
			) );
		
		$this->core = new KlinkCoreClient($config);
		
		$result = $this->invokeMethod($this->core, '_get_connection', array('public'));

		$this->assertNotNull($result, 'result must exists');
		
		$this->assertInstanceOf('KlinkRestClient', $result);
		
	}






	// to test private methods

	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}
	    

}