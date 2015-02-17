<?php

/**
* Test the KlinCoreClient Class for basic functionality
*/
class KlinkCoreClientTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The institution identifier to be used in the test
	 *
	 * @var string
	 */
	const INSTITUION_ID = 'KLINK';

	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');

	  	$config = new KlinkConfiguration( self::INSTITUION_ID, 'KA', array(
	  			new KlinkAuthentication( 'https://klink-dev0.cloudapp.net/kcore/', 'admin@klink.org', 'admin.klink' )
	  		) );

	  	// $config->enableDebug();

	    $this->core = new KlinkCoreClient($config);

	}

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

		$this->assertEquals('public', $result->getVisibility());

		$this->assertFalse($result->getFacets());

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

	public function testPrivateSearch(){
		
		$term_to_search = '*';

		$result = $this->core->search($term_to_search, 'private');

		$this->assertEquals($term_to_search, $result->getTerms());

		$this->assertInstanceOf('KlinkSearchResult', $result);

		$this->assertEquals('private', $result->getVisibility());

		$this->assertFalse($result->getFacets());

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

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSearchFacetParameterValidation(){

		$result = $this->core->search($term_to_search. 'public', 10, 0, $facets = null);


	}


	public function testSearchWithFacets(){
		
		// TODO: add value for the facets parameter

		$term_to_search = '*';



		$result = $this->core->search($term_to_search. 'public', 10, 0, $facets = null);

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


	public function testIndexAndRemoveDocument()
	{

		$content = 'This is the <strong>content</strong>';

		$hash = KlinkDocumentUtils::generateHash($content);

		$document_id = 'test';

		$descriptor = KlinkDocumentDescriptor::create(
			self::INSTITUION_ID, $document_id, $hash, 'Title', 
			'text/html', 'http://localhost/test/document', 
			'http://localhost/test/thumbnail', 'user <user@user.com>', 'user <user@user.com>');

		$document = new KlinkDocument($descriptor, $content);

		// Add test
		$add_response = $this->core->addDocument( $document );

		$this->assertInstanceOf('KlinkDocumentDescriptor', $add_response);

		// Get test
		$get_response = $this->core->getDocument( self::INSTITUION_ID, $document_id );

		$this->assertInstanceOf('KlinkDocumentDescriptor', $get_response);

		$this->assertEquals($hash, $get_response->getHash(), 'different hash');

		// Remove test
		$remove_response = $this->core->removeDocument( $descriptor );

		// Get confirms
		try{
			
			$get_response = $this->core->getDocument( self::INSTITUION_ID, $document_id );

			$this->assertFalse(true, 'The confirmation should repond with a Not found exception');

		}catch(KlinkException $kex){

			$this->assertEquals(404, $kex->getCode(), 'Expecting not found');
		}
		
	}

	public function testCreateAndRemoveInstitution()
	{
		$inst = KlinkInstitutionDetails::create('testInst', 'testInst');

		$inst->setMail('mail@mail.com');
		$inst->setPhoneNumber('+55555555');
		$inst->setThumbnail('http://thumbnail.org');
		$inst->setUrl('http://institution.org');

		$save_response = $this->core->saveInstitution($inst);

		$this->assertInstanceOf('KlinkInstitutionDetails', $save_response);

		$this->assertEquals($inst->getId(), $save_response->getId(), 'different id');

		$this->core->deleteInstitution('testInst');

	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFacetsWithWrongParameter()
	{
		$answer = $this->core->facets(array('hi', 'facet!'));
	}

	public function testFacets()
	{

		

		// TODO: assert search without facets call to getFacets() should return false

		$answer = $this->core->facets();

		print_r($answer);

		// todo: test if array of facets

		// test: if no facet is enabled the facets array in the result should be empty
	}
}