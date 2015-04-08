<?php

/**
* Test the KlinkFacet Class for basic functionality
* 
* 
* 
* Non-static method KlinkFacetsBuilder::documentType() should not be called statically, assuming $this from incompatible context
*/
class KlinkFacetsBuilderTest extends PHPUnit_Framework_TestCase
{
	

	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');

	  	error_reporting(E_ALL & E_STRICT);


	  	$oClass = new ReflectionClass('KlinkFacet');

        $this->currently_supported = array(KlinkFacet::DOCUMENT_TYPE,KlinkFacet::LANGUAGE,KlinkFacet::INSTITUTION_ID,KlinkFacet::DOCUMENT_GROUPS);

	}


	public function valid_facetNamesProvider()
	{

		$names = $this->currently_supported;

		$valids = array();

		foreach ($names as $name) {
			array_push($valids, array(''.$name.''));
		}

		return $valids;
	}


	public function invalid_facetNamesProvider()
	{
		return array(
			array('mob'),
			array('phone'),
			array('john_smith'),
			array('judo'),
		);
	}

	public function valid_documentTypes()
	{
		return array(
			array('web-page'),
			array('document'),
			array('spreadsheet'),
			array('presentation'),
			array('presentation,document'),
			array('presentation,web-page,spreadsheet'),
			array('uri-list'),
		);
	}

	public function invalid_documentTypes()
	{
		return array(
			array('banas'),
			array('orange'),
			array('jiuce,apple'),
		);
	}


	public function valid_params()
	{
		return array(
			array(array(), null),
			array(array('string'), array('filter' => 'string', 'mincount' => KlinkFacetsBuilder::DEFAULT_MINCOUNT, 'count' => KlinkFacetsBuilder::DEFAULT_COUNT, 'prefix' => null)),
			array(array(1), array('filter' => null, 'mincount' => 1, 'count' => KlinkFacetsBuilder::DEFAULT_COUNT, 'prefix' => null)),
			array(array(1,2), array('filter' => null, 'mincount' => 2, 'count' => 1, 'prefix' => null)),
			array(array('string', 1, 2), array('filter' => 'string', 'mincount' => 2, 'count' => 1, 'prefix' => null)),
		);
	}

	public function invalid_params()
	{
		return array(
			array(array('string1', 'string2')),
			array(array(1,2,3)),
			array(array('string', 1)),
			array(array('string', 'string', 1)),
			array(array('string', 1,2,3)),
		);
	}


	public function facets_methods()
	{
		return array(
			array(KlinkFacet::DOCUMENT_TYPE, KlinkFacet::DOCUMENT_TYPE),
			array(KlinkFacet::DOCUMENT_GROUPS, KlinkFacet::DOCUMENT_GROUPS),
			array(KlinkFacet::INSTITUTION_ID, KlinkFacet::INSTITUTION_ID),
			array(KlinkFacet::LANGUAGE, KlinkFacet::LANGUAGE),
			// array(KlinkFacet::LOCAL_DOCUMENT_ID, KlinkFacet::LOCAL_DOCUMENT_ID),
			// array(KlinkFacet::DOCUMENT_ID, KlinkFacet::DOCUMENT_ID),
		);
	}

	public function filters_methods()
	{
		return array(
			array(KlinkFacet::LOCAL_DOCUMENT_ID, KlinkFacet::LOCAL_DOCUMENT_ID),
			array(KlinkFacet::DOCUMENT_ID, KlinkFacet::DOCUMENT_ID),
		);
	}






	public function testBuilderAllNames()
	{
		
		$current = KlinkFacetsBuilder::allNames();

		$this->assertEquals( $this->currently_supported, $current);

	}

	/**
	 * @dataProvider facets_methods
	 */
	public function testBuilderDefaults($method, $expected_active_facet)
	{
		
		$ft = KlinkFacetsBuilder::create()->{$method}()->build();

		$this->assertNotEmpty($ft);

		$this->assertCount(1, $ft);

		$first = $ft[0];

		$this->assertEquals( $expected_active_facet, $first->getName());

	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider filters_methods
	 */
	public function testFiltersInvalidInput($method, $value){

		$ft = KlinkFacetsBuilder::create()->{$method}()->build();

	}

	public function testFilters()
	{

		$ft = KlinkFacetsBuilder::create()->localDocumentId('10')->build();

		$this->assertNotEmpty($ft);

		$this->assertCount(1, $ft);

		$first = $ft[0];


		$ft = KlinkFacetsBuilder::create()->localDocumentId(array('10', '12'))->build();

		$this->assertNotEmpty($ft);

		$this->assertCount(1, $ft);

		$first = $ft[0];

		$this->assertEquals('10,12', $first->getFilter());

	}

	// /**
	//  * @dataProvider facets_methods
	//  */
	// public function testMagicStaticMethodCall($facet, $expected_active_facet)
	// {
		
		// $builder = @KlinkFacetsBuilder::{$facet}();

		// $this->assertInstanceOf('KlinkFacetsBuilder', $builder);

		// $ft = $builder->build();

		// $this->assertNotEmpty($ft);

		// $this->assertCount(1, $ft);

		// $first = $ft[0];

		// $this->assertEquals( $expected_active_facet, $first->getName());

	// }

	// /**
	//  * @dataProvider invalid_facetNamesProvider
	//  * @expectedException BadMethodCallException
	//  */
	// public function testMagicStaticMethodCallWithInvalidFacet($facet)
	// {
		
	// 	$builder = KlinkFacetsBuilder::{$facet}();

	// 	$this->markTestIncomplete(
 //          'This test has not been implemented yet.'
 //        );

	// }


	public function testBuilderAll()
	{

		$facets = KlinkFacetsBuilder::all();

		$this->assertEquals(count($this->currently_supported), count($facets));

		$this->assertContainsOnlyInstancesOf('KlinkFacet',$facets);

	}

	/**
	 * @expectedException BadMethodCallException
	 */
	public function testTwoOrMoreTimesCall($facet)
	{
		
		KlinkFacetsBuilder::create()->documentType()->documentType();

	}


	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInstitutionFacetWithInvalidId()
	{
		
		KlinkFacetsBuilder::i()->institution('IM-NOT-AN_ID');

	}


	/**
	 * @dataProvider invalid_documentTypes
	 * @expectedException InvalidArgumentException
	 */
	public function testDocumentTypeFacetWithInvalidType($documentType)
	{
		
		KlinkFacetsBuilder::i()->documentType($documentType);

	}

	/**
	 * [testDocumentTypeFacetWithValidType description]
	 * @dataProvider valid_documentTypes
	 */
	public function testDocumentTypeFacetWithValidType($documentType)
	{
		
		$facets = KlinkFacetsBuilder::i()->documentType($documentType)->build();

		$this->assertContainsOnlyInstancesOf('KlinkFacet', $facets);

		$this->assertCount(1, $facets, 'message');

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
	 * @dataProvider valid_params
	 */
	public function testParametersHandlingOnFacetMethods($params, $expected)
	{
		
		$what = $this->invokeMethod(new KlinkFacetsBuilder(), '_handle_facet_parameters', $params);

		$this->assertEquals($expected, $what);
	}

	/**
	 * @dataProvider invalid_params
	 * @expectedException BadMethodCallException
	 */
	public function testParametersHandlingOnFacetMethodsInvalid($params)
	{
		
		$what = $this->invokeMethod(new KlinkFacetsBuilder(), '_handle_facet_parameters', $params);

	}

	// public function testByHand()
	// {
		
	// 	KlinkFacetsBuilder::documentType();

	// 	KlinkFacetsBuilder::documentType('document');

	// }

}