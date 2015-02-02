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
	  			new KlinkAuthentication( 'https://klink-dev0.cloudapp.net/kcore/', 'admin@klink.org', 'admin.klink' )
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
	
	public function testGetInstitutions()
	{

		$result = $this->core->getInstitutions();

		$this->assertTrue(is_array($result), 'result must be an array');

		$this->assertContainsOnlyInstancesOf('KlinkInstitutionDetails', $result);

	}

	public function testSearch(){
		
		$term_to_search = '*';

		$result = $this->core->search($term_to_search);

		$this->assertEquals($term_to_search, $result->getTerms());

		$this->assertInstanceOf('KlinkSearchResult', $result);

		$items = $result->getResults();

		if(!empty($items)){

			// just check if the deserialization has done a good job (at least for the first element)

			$this->assertContainsOnlyInstancesOf('KlinkSearchResultItem', $items);

			$first = $items[0];

			$this->assertInstanceOf('KlinkDocumentDescriptor', $first->getDescriptor());

			$this->assertNotNull($first->getDescriptor(), 'Null descriptor');

			$this->assertNotNull($first->title, 'Null title, the magic __get is not working');

			$this->assertNotNull($first->getInstitutionID(), 'Null instituionid, the magic __call is not working');
		}
	}

}