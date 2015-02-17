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

        $this->currently_supported = $oClass->getConstants();

	}


	public function valid_facetNamesProvider()
	{

		$names = array_values($this->currently_supported);

		$valids = [];

		foreach ($names as $name) {
			array_push($valids, [''.$name.'']);
		}

		return $valids;
	}


	public function invalid_facetNamesProvider()
	{
		return [
			['mob'],
			['phone'],
			['john_smith'],
			['judo'],
		];
	}


	public function valid_params()
	{
		return [
			[[], null],
			[['string'], ['filter' => 'string', 'mincount' => KlinkFacetsBuilder::DEFAULT_MINCOUNT, 'count' => KlinkFacetsBuilder::DEFAULT_COUNT, 'prefix' => null]],
			[[1], ['filter' => null, 'mincount' => KlinkFacetsBuilder::DEFAULT_MINCOUNT, 'count' => 1, 'prefix' => null]],
			[[1,2], ['filter' => null, 'mincount' => 2, 'count' => 1, 'prefix' => null]],
			[['string', 1, 2], ['filter' => 'string', 'mincount' => 2, 'count' => 1, 'prefix' => null]],
		];
	}

	public function invalid_params()
	{
		return [
			[['string1', 'string2']],
			[[1,2,3]],
			[['string', 1]],
			[['string', 'string', 1]],
			[['string', 1,2,3]],
		];
	}






	public function testBuilderAllNames()
	{
		
		$current = KlinkFacetsBuilder::allNames();

		$this->assertEquals( array_values($this->currently_supported), $current);

	}

	// /**
	//  * @dataProvider valid_facetNamesProvider
	//  */
	// public function testMagicStaticMethodCall($facet)
	// {
		
	// 	$builder = KlinkFacetsBuilder::{$facet}();

	// 	$this->assertInstanceOf('KlinkFacetsBuilder', $builder);

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
	 * @dataProvider invalid_facetNamesProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testDocumentTypeFacetWithInvalidType($documentType)
	{
		
		KlinkFacetsBuilder::i()->documentType($documentType);

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
	    $reflection = new \ReflectionClass(get_class($object));
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