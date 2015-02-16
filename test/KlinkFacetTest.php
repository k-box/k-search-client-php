<?php

/**
* Test the KlinkFacet Class for basic functionality
*/
class KlinkFacetTest extends PHPUnit_Framework_TestCase
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
	}

	// testGetters

	public function testFacetCreate()
	{

		$facet_one = KlinkFacet::create("test");

		$this->assertEquals('test', $facet_one->getName());
		$this->assertEquals(2, $facet_one->getMin());
		$this->assertEquals(10, $facet_one->getCount());
		$this->assertEquals(null, $facet_one->getFilter());
		$this->assertEquals(null, $facet_one->getPrefix());

		$this->assertEquals(array(
				"facets" => 'test',
				"facet_test_count" => 10,
				"facet_test_mincount" => 2,
			), $facet_one->toKlinkParameter());

		$facet_two = KlinkFacet::create("test", 10, 'prefix', 12, 'filter');

		$this->assertEquals('test', $facet_two->getName());
		$this->assertEquals(10, $facet_two->getMin());
		$this->assertEquals(12, $facet_two->getCount());
		$this->assertEquals('filter', $facet_two->getFilter());
		$this->assertEquals('prefix', $facet_two->getPrefix());

		$this->assertEquals(array(
				"facets" => 'test',
				"facet_test_count" => 12,
				"facet_test_mincount" => 10,
				"filter_test" => 'filter',
				"facet_test_prefix" => 'prefix',
			), $facet_two->toKlinkParameter());
	}
}