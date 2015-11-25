<?php

/**
* Test the KlinkFacet Class for basic functionality
*/
class KlinkFacetTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testInvalidFacetName()
	{

		$facet_one = KlinkFacet::create("test");


	}

	public function testFacetCreate()
	{

		$test_facet_name = KlinkFacet::DOCUMENT_TYPE;

		$facet_one = KlinkFacet::create($test_facet_name);

		$this->assertEquals(KlinkFacet::DOCUMENT_TYPE, $facet_one->getName());
		$this->assertEquals(2, $facet_one->getMin());
		$this->assertEquals(10, $facet_one->getCount());
		$this->assertEquals(null, $facet_one->getFilter());
		$this->assertEquals(null, $facet_one->getPrefix());
		$this->assertEquals(array(
				"facets" => $test_facet_name,
				"facet_".$test_facet_name."_count" => 10,
				"facet_".$test_facet_name."_mincount" => 2,
			), $facet_one->toKlinkParameter());


		$facet_two = KlinkFacet::create($test_facet_name, 10, 'prefix', 12, 'filter');

		$this->assertEquals(KlinkFacet::DOCUMENT_TYPE, $facet_two->getName());
		$this->assertEquals(10, $facet_two->getMin());
		$this->assertEquals(12, $facet_two->getCount());
		$this->assertEquals('filter', $facet_two->getFilter());
		$this->assertEquals('prefix', $facet_two->getPrefix());
		$this->assertEquals(array(
				"facets" => $test_facet_name,
				"facet_".$test_facet_name."_count" => 12,
				"facet_".$test_facet_name."_mincount" => 10,
				"filter_$test_facet_name" => 'filter',
				"facet_".$test_facet_name."_prefix" => 'prefix',
			), $facet_two->toKlinkParameter());

	}
}