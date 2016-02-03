<?php

/**
* Test the KlinCoreClient Class for real usage on a real K-Link Core instance.__CLASS__
* All tests are integration tests
*/
class KlinkCoreClientIntegrationTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{	  
		date_default_timezone_set('Europe/Rome');

	  	error_reporting(E_ALL & E_STRICT);
		  
		$config = new KlinkConfiguration( INSTITUION_ID, 'KA', array(
				new KlinkAuthentication( $_SERVER['PUBLIC_CORE_URL'], $_SERVER['CORE_USER'], $_SERVER['CORE_PASS'], \KlinkVisibilityType::KLINK_PUBLIC ),
				new KlinkAuthentication( $_SERVER['CORE_URL'], $_SERVER['CORE_USER'], $_SERVER['CORE_PASS'], \KlinkVisibilityType::KLINK_PRIVATE )
			) );

		if(in_array('--debug', $_SERVER['argv'])){
			$config->enableDebug();	
		}

		

		$this->core = new KlinkCoreClient($config);

	}



	public function invalid_facet_param()
	{
		return array(
			array(array('string1')),
			array(array(1)),
			array(array('string1', 'string2')),
			array(array(1,2,3)),
			array(array('string', 1)),
			array(array('string', 'string', 1)),
			array(array('string', 1,2,3)),
		);
	}




	/**
	 * @group integration
	 */
	public function testGetInstitutions()
	{

		$result = $this->core->getInstitutions();

		$this->assertTrue(is_array($result), 'result must be an array');

		$this->assertContainsOnlyInstancesOf('KlinkInstitutionDetails', $result);

	}
	
	/**
	 * @group integration
	 */
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

		$this->assertFalse($result->getFacets());

	}

	/**
	 * @group integration
	 */
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
	 * @dataProvider invalid_facet_param
	 * @group integration
	 */
	public function testSearchFacetParameterValidation($facets){

		$result = $this->core->search('term', 'public', 10, 0, $facets);

	}

	/**
	 * @group integration
	 */
	public function testSearchWithFacets(){
		
		$term_to_search = '*';

		$f = KlinkFacetsBuilder::all();

		$result = $this->core->search($term_to_search, 'public', 10, 0, $f);

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

		$facets = $result->getFacets();

		$this->assertTrue(!empty($facets));

		$this->assertEquals(count($f), count($facets));

		$this->assertContainsOnlyInstancesOf('KlinkFacet', $facets);		
	}

	/**
	 * @group integration
	 */
	public function testSearchWithFilters(){
		
		$term_to_search = '*';

		$f = KlinkFacetsBuilder::create()->localDocumentId('aaaa')->build();

		$result = $this->core->search($term_to_search, 'public', 10, 0, $f);

		$this->assertEquals($term_to_search, $result->getTerms());

		$this->assertInstanceOf('KlinkSearchResult', $result);

		$filters = $result->filters;

		$this->assertNotNull($filters, 'Null Filters');

		$this->assertTrue(!empty($filters));

		$this->assertEquals(count($f), count($filters));

	}

	/**
	 * @group integration
	 */
	public function testIndexAndRemoveDocument()
	{

		$content = 'This is the <strong>content</strong>';

		$hash = KlinkDocumentUtils::generateHash($content);

		$document_id = 'test';

		$descriptor = KlinkDocumentDescriptor::create(
			INSTITUION_ID, $document_id, $hash, 'Title', 
			'text/html', 'http://localhost/test/document', 
			'http://localhost/test/thumbnail', 'user <user@user.com>', 'user <user@user.com>');

		$document = new KlinkDocument($descriptor, $content);

		// Add test
		$add_response = $this->core->addDocument( $document );

		$this->assertInstanceOf('KlinkDocumentDescriptor', $add_response);

		// Get test
		$get_response = $this->core->getDocument( INSTITUION_ID, $document_id );

		$this->assertInstanceOf('KlinkDocumentDescriptor', $get_response);

		$this->assertEquals($hash, $get_response->getHash(), 'different hash');

		// Remove test
		$remove_response = $this->core->removeDocument( $descriptor );

		// Get confirms
		try{
			
			$get_response = $this->core->getDocument( INSTITUION_ID, $document_id );

			$this->assertFalse(true, 'The confirmation should repond with a Not found exception');

		}catch(KlinkException $kex){

			$this->assertEquals(404, $kex->getCode(), 'Expecting not found');
		}
		
	}

	/**
	 * @group integration
	 */
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
	 * @dataProvider invalid_facet_param
	 * @group integration
	 */
	public function testFacetsWithWrongParameter($param)
	{
		$answer = $this->core->facets($param);
	}

	/**
	 * @group integration
	 */
	public function testFacets()
	{

		$f = KlinkFacetsBuilder::all();	

		$answer = $this->core->facets($f);

		$this->assertTrue(!empty($answer));

		$this->assertEquals(count($f), count($answer));

		$this->assertContainsOnlyInstancesOf('KlinkFacet', $answer);



		$f = KlinkFacetsBuilder::create()->documentType('document')->build();	

		$answer = $this->core->facets($f);

		$this->assertTrue(!empty($answer));

		$this->assertEquals(count($f), count($answer));

		$this->assertContainsOnlyInstancesOf('KlinkFacet', $answer);

		
	}

	/**
	 * @group integration
	 */
	public function testFacetArrayCollapse()
	{
		$f = KlinkFacetsBuilder::all();

		$f_count = count($f);

		$collapsed = $this->invokeMethod($this->core, '_collapse_facets', array($f));

		$this->assertArrayHasKey('facets', $collapsed);

		$this->assertEquals($f_count, count(explode(',', $collapsed['facets'])));



		$collapsed_two = $this->invokeMethod($this->core, '_collapse_facets', array(null));

		$this->assertEquals(array(), $collapsed_two);

	}


	/**
	 * @group integration
	 */
	public function testPublicDocumentsCount()
	{
		
		$count = $this->core->getPublicDocumentsCount();

		$this->assertNotNull($count);

		$this->assertTrue(is_integer($count));

		$this->assertTrue($count >= 0);


		$count_two = $this->core->getPublicDocumentsCount(INSTITUION_ID);

		$this->assertNotNull($count_two);

		$this->assertTrue(is_integer($count_two));

		$this->assertTrue($count_two >= 0);



		$this->assertEquals($count, $count_two);
	}


	/**
	 * @group integration
	 */
	public function testPrivateDocumentsCount($value='')
	{

		$count = $this->core->getPrivateDocumentsCount();

		$this->assertNotNull($count);

		$this->assertTrue(is_integer($count));

		$this->assertTrue($count >= 0);


		$count_two = $this->core->getPrivateDocumentsCount(INSTITUION_ID);

		$this->assertNotNull($count_two);

		$this->assertTrue(is_integer($count_two));

		$this->assertTrue($count_two >= 0);



		$this->assertEquals($count, $count_two);
	}





	// to test private methods

	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

	/**
	 * @group integration
	 */
	public function testTestMethod(){

		// Correct configuration
        
		
		$config = new KlinkConfiguration( INSTITUION_ID, 'KA', array(
				new KlinkAuthentication( $_SERVER['CORE_URL'], $_SERVER['CORE_USER'], $_SERVER['CORE_PASS'] )
	  		) );

	  	if(in_array('--debug', $_SERVER['argv'])){
	  		$config->enableDebug();	
	  	}

	  	$error = null;

	    $test_result = KlinkCoreClient::test($config, $error);

	    $this->assertTrue($test_result);
        
        $config = new KlinkConfiguration( INSTITUION_ID, 'KA', array(
				new KlinkAuthentication( $_SERVER['PUBLIC_CORE_URL'], $_SERVER['CORE_USER'], $_SERVER['CORE_PASS'], \KlinkVisibilityType::KLINK_PUBLIC )
	  		) );

	  	if(in_array('--debug', $_SERVER['argv'])){
	  		$config->enableDebug();	
	  	}

	  	$error = null;

	    $test_result = KlinkCoreClient::test($config, $error);

	    $this->assertTrue($test_result);


	    // Wrong username
	     
	    $config = new KlinkConfiguration( INSTITUION_ID, 'KA', array(
				new KlinkAuthentication( $_SERVER['CORE_URL'], $_SERVER['CORE_USER'].'ciccio', $_SERVER['CORE_PASS'] )
	  		) );

	  	if(in_array('--debug', $_SERVER['argv'])){
	  		$config->enableDebug();	
	  	}

	  	$error = null;

	    $test_result = KlinkCoreClient::test($config, $error);

	    $this->assertFalse($test_result);

	    $this->assertEquals(401, $error->getCode());

	    $this->assertEquals('Wrong username or password.', $error->getMessage());
	    
	    // Wrong Core URL
	    
	    $config = new KlinkConfiguration( INSTITUION_ID, 'KA', array(
				new KlinkAuthentication( $_SERVER['PUBLIC_CORE_URL'].'2', $_SERVER['CORE_USER'], $_SERVER['CORE_PASS'], \KlinkVisibilityType::KLINK_PUBLIC )
	  		) );

	  	if(in_array('--debug', $_SERVER['argv'])){
	  		$config->enableDebug();	
	  	}

	  	$error = null;

	    $test_result = KlinkCoreClient::test($config, $error);

	    $this->assertFalse($test_result);

	    $this->assertEquals(404, $error->getCode());

	    $this->assertEquals('Server not found or network problem.', $error->getMessage());

	    

	}
}