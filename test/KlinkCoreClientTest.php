<?php

/**
* Test the KlinCoreClient Class for basic functionality
*/
class KlinkCoreClientTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');

	  	$config = new KlinkConfiguration( 'K-uyv', 'KA', array(
	  			new KlinkAuthentication( 'https://klink-core0.cloudapp.net/kcore/', 'admin@klink.org', 'admin.klink' )
	  		) );

	  	// $config->enableDebug();

	    $this->core = new KlinkCoreClient($config);

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
	
	// public function testGetInstitutions()
	// {


	// 	$result = $this->core->getInstitutions();

	// 	// print_r($result);

	// 	$this->assertTrue(is_array($result), 'result must be an array');

	// 	$this->assertContainsOnlyInstancesOf('KlinkInstitutionDetails', $result);

	// }

	public function testSearch(){
		
		$term_to_search = 'search_term';

		$result = $this->core->search($term_to_search);

		// print_r( $result );

		$this->assertEquals($term_to_search, $result->getTerms());

		$this->assertInstanceOf('KlinkSearchResult', $result);
	}

}