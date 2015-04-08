<?php

/**
* Test the KlinkGeoJsonFeature and KlinkGeoJsonGeometry Class construction
*/
class KlinkGeoJsonTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
	}

	

	public function testCreateGeometry()
	{

		$geom = KlinkGeoJsonGeometry::createPoint(40.24934, 74.33804);

		$this->assertEquals(KlinkGeoJsonGeometry::TYPE_POINT, $geom->getType());

		$this->assertEquals(array(40.24934, 74.33804), $geom->getCoordinates(), 'Coordinates don\'t match', 0.01);

		$geom = KlinkGeoJsonGeometry::create(array(40.24934, 74.33804), KlinkGeoJsonGeometry::TYPE_POINT);

		$this->assertEquals(KlinkGeoJsonGeometry::TYPE_POINT, $geom->getType());

		$this->assertEquals(array(40.24934, 74.33804), $geom->getCoordinates(), 'Coordinates don\'t match', 0.01);

	}

	public function testCreateFeature()
	{
		$properties = array('name' => 'Test');

		$feature = KlinkGeoJsonFeature::create(KlinkGeoJsonGeometry::createPoint(40.24934, 74.33804), $properties);

		$this->assertEquals('Feature', $feature->getType());

		$this->assertEquals(array(40.24934, 74.33804), $feature->getGeometry()->getCoordinates(), 'Coordinates don\'t match', 0.01);

		$this->assertEquals($properties, $feature->getProperties(), 'Properties don\'t match');

	}

}