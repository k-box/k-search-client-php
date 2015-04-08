<?php

/**
* Test the Thumbnail generation invocation functionality. Make sure to run agains a core with
* versione 2.2 of the API
*/
class KlinkThumbnailGenerationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The institution identifier to be used in the test
	 *
	 * @var string
	 */
	// const INSTITUION_ID = 'KLINK';

	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');

	  	// error_reporting(E_ALL & E_STRICT);

	  	set_error_handler("var_dump");

	  	ini_set("display_errors", 1);
		ini_set("track_errors", 1);
		// ini_set("html_errors", 1);
		error_reporting(E_ALL);

	  	$config = new KlinkConfiguration( INSTITUION_ID, 'KA', array(
	  			new KlinkAuthentication( CORE_URL, CORE_USER, CORE_PASS )
	  		) );

	  	if(in_array('--debug', $_SERVER['argv'])){
	  		$config->enableDebug();	
	  	}

	  	

	    $this->core = new KlinkCoreClient($config);

	}






	public function testGenerateThumbnailOfWebSites()
	{



		$result = $this->core->generateThumbnailOfWebSite('http://www.google.it/');

		$this->assertNotNull($result);

		// $this->assertTrue(is_array($result), 'result must be an array');

		// $this->assertContainsOnlyInstancesOf('KlinkInstitutionDetails', $result);

		// file_put_contents(__DIR__ . '/json/siteimage.png', data)

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