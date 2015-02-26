<?php

/**
* Test the KlinkDocumentDescriptor Class for basic functionality
*/
class KlinkDocumentDescriptorTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
	}

	

	public function testDocumentGroupsAddAndRemove()
	{

		$doc = new KlinkDocumentDescriptor();

		$this->assertEmpty($doc->getDocumentGroups());

		$doc->addDocumentGroup(0, 1);

		$this->assertNotEmpty($doc->getDocumentGroups());

		$this->assertCount(1, $doc->getDocumentGroups());

		$first = $doc->getDocumentGroups();

		$this->assertEquals('0:1', $first[0]);

		$doc->addDocumentGroup(5, 2);

		$this->assertCount(2, $doc->getDocumentGroups());

		$doc->removeDocumentGroup(0, 1);

		$this->assertCount(1, $doc->getDocumentGroups());

		$first = $doc->getDocumentGroups();

		$this->assertEquals('5:2', $first[0]);

	}

	public function testTitleAliasAddAndRemove()
	{

		$doc = new KlinkDocumentDescriptor();

		$this->assertEmpty($doc->getTitleAliases());

		$doc->addTitleAlias('title');

		$this->assertNotEmpty($doc->getTitleAliases());

		$this->assertCount(1, $doc->getTitleAliases());

		$first = $doc->getTitleAliases();

		$this->assertEquals('title', $first[0]);


		$doc->addTitleAlias('second title');

		$this->assertCount(2, $doc->getTitleAliases());

		$doc->removeTitleAlias('title');

		$this->assertCount(1, $doc->getTitleAliases());

		$first = $doc->getTitleAliases();

		$this->assertEquals('second title', $first[0]);

	}
}