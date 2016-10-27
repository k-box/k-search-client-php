<?php

/**
* Test the KlinkFacetItem Class for basic functionality
*/
class KlinkFacetItemTest extends PHPUnit_Framework_TestCase
{

	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
	}



	public function testFacetItem()
	{

		$item = new KlinkFacetItem();

		$item->term = 'en';
		$item->count = '2';

		$this->assertEquals('en', $item->getTerm());
		$this->assertEquals('2', $item->getCount());
		$this->assertEquals('2', $item->getOccurrenceCount());

	}
	
	
}