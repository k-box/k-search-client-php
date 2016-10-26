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

	public function serializedDescriptorsDataprovider(){
		return [
			[__DIR__ . '/json/documentdescriptor-v2.1.json', '2.1'],
			[__DIR__ . '/json/documentdescriptor-v2.2.json', '2.2'],
		];
	}

	public function projectsDataprovider(){
		return [
			[['1', '2', array()]],
			[['1', '2', new stdClass]],
			[['1', '2', true]],
			[['1', '2', false]],
		];
	}

	public function invalidProjectsDataprovider(){
		return [
			[array()],
			[new stdClass],
			[true],
			[false],
		];
	}

	public function validKlinkIdComponentDataprovider(){
		return [
			['K', '1', 'K-1'],
			['SomeInstitution', 'SomeDocumentIdentifier', 'SomeInstitution-SomeDocumentIdentifier'],
		];
	}

	public function invalidKlinkIdComponentDataprovider(){
		return [
			[array(), '1'],
			[new stdClass, '1'],
			['1', array()],
			['1', new stdClass],
			[true, '1'],
			[false, '1'],
			['1', true],
			['1', false],
			[null, '1'],
			['1', null],
			[1, 'hello'],
			['hello', 1],
			['invalid-institution', 1],
			['hello', 'invalid-localdocumentidentifier'],
		];
	}

	public function createTestDataprovider(){
		return [
			[
				'inst',
				'docid', 
				'hashhashhashhash', 
				'document title', 
				'application/pdf',
				'https://something.com/doc',
				'https://something.com/thumb',
				'owner <owner@something.com>',
				'uploaded <uploader@something.com>',
				null,
				null
			],
			[
				'inst',
				'docid', 
				'hashhashhashhash', 
				'document title', 
				'application/pdf',
				'https://something.com/doc',
				'https://something.com/thumb',
				'owner <owner@something.com>',
				'uploaded <uploader@something.com>',
				'private',
				null
			],
			[
				'inst',
				'docid', 
				'hashhashhashhash', 
				'document title', 
				'application/pdf',
				'https://something.com/doc',
				'https://something.com/thumb',
				'owner <owner@something.com>',
				'uploaded <uploader@something.com>',
				'public',
				null
			],
			[
				'inst',
				'docid', 
				'hashhashhashhash', 
				'document title', 
				'application/pdf',
				'https://something.com/doc',
				'https://something.com/thumb',
				'owner <owner@something.com>',
				'uploaded <uploader@something.com>',
				'private',
				'2016-10-26T21:01:20+02:00'
			],
		];
	}

    public function testDocumentProjectIDAddAndRemove()
    {
        $doc = new KlinkDocumentDescriptor();

        $this->assertEmpty($doc->getProjects());

        $doc->addProject(10);
        $doc->addProject(20);

        $this->assertCount(2, $doc->getProjects());
        $this->assertEquals(array(10, 20), $doc->getProjects());

        $this->assertFalse($doc->removeProject(2));
        $this->assertTrue($doc->removeProject(20));

        $this->assertCount(1, $doc->getProjects());
        $this->assertEquals(array(10), $doc->getProjects());

		$doc->setProjects([]);
		$this->assertCount(0, $doc->getProjects());

		$doc->addProject(20);
		$doc->setProjects([10, 20]);
        $this->assertEquals(array(10, 20), $doc->getProjects());

    }

	/**
	 * @dataProvider projectsDataprovider
	 * @expectedException InvalidArgumentException
	 */
	public function testDocumentSetProjectsParameterValidation($data){
		
		$doc = new KlinkDocumentDescriptor();

        $doc->setProjects($data);
	}

	/**
	 * @dataProvider invalidProjectsDataprovider
	 * @expectedException InvalidArgumentException
	 */
	public function testDocumentAddProjectsParameterValidation($data){
		
		$doc = new KlinkDocumentDescriptor();

        $doc->addProject($data);
	}

	/**
	 * @dataProvider invalidProjectsDataprovider
	 * @expectedException InvalidArgumentException
	 */
	public function testDocumentRemoveProjectsParameterValidation($data){
		
		$doc = new KlinkDocumentDescriptor();

        $doc->removeProject($data);
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

	/**
	 * @group deserialization
	 * @dataProvider serializedDescriptorsDataprovider
	 */
	public function testDeserialization($file, $apiVersion)
	{

		$json = file_get_contents( $file );

		$this->jm = new JsonMapper();
		$this->jm->bExceptionOnUndefinedProperty = true;

		$decoded = json_decode($json, false);
		
		$deserialized = $this->jm->map($decoded, new KlinkDocumentDescriptor());

		$this->assertInstanceOf('KlinkDocumentDescriptor', $deserialized);
		
		$this->assertEquals('KLINK', $deserialized->getInstitutionID());
		$this->assertEquals('48f6f2', $deserialized->getLocalDocumentID());
		$this->assertEquals('public', $deserialized->getVisibility());
		$this->assertEquals('48f6f26b4aa5b2c6c0ce54082ab4366dd6e6eb6af4d2a6ff85659e2afb96120aa9b9ba28ed32b5103f62136474a4d6a5c4546bad4560294aa924dedb754bc7b1', $deserialized->getHash());
		$this->assertEquals('SeedInfo_48.pdf', $deserialized->getTitle());
		$this->assertEquals('http://somesite.com/document/SeedInfo_48.pdf', $deserialized->getDocumentURI());
		$this->assertEquals(array("Dushanbe","Kyrgyzstan","Europe","Yuzhniy Ferganskiy Kanal Imeni Andreyeva"), $deserialized->getLocationsString());
		$this->assertEquals('KLINK-48f6f2', $deserialized->getKlinkId());
		$this->assertNull($deserialized->getAuthors());
		$this->assertEmpty($deserialized->getAbstract());
		$this->assertEquals('application/pdf', $deserialized->getMimeType());
		$this->assertEquals('document', $deserialized->getDocumentType());
		$this->assertEquals('2015-03-16T12:17:38+0000', $deserialized->getCreationDate());
		$this->assertEquals('en', $deserialized->getLanguage());
		$this->assertEquals('http://somesite.com/thumbnail/48f6f2.png', $deserialized->getThumbnailURI());
		$this->assertEquals('Oksana <alexfut_7@yahoo.com>', $deserialized->getUserUploader());
		$this->assertEquals('Oksana <alexfut_7@yahoo.com>', $deserialized->getUserOwner());

		if($apiVersion==='2.2'){
			$this->assertEquals(["1","2"], $deserialized->getProjects());
		}
		else {
			$this->assertEmpty($deserialized->getProjects());
		}
		
	}

	/**
	 * @group deserialization
	 * @dataProvider serializedDescriptorsDataprovider
	 */
	public function testGeoJsonDeserialization($file)
	{

		$json = file_get_contents($file);

		$this->jm = new JsonMapper();
		$this->jm->bExceptionOnUndefinedProperty = true;

		$decoded = json_decode($json, false);

		$deserialized = $this->jm->map($decoded, new KlinkDocumentDescriptor());

		$this->assertInstanceOf('KlinkDocumentDescriptor', $deserialized);

		$location_strings = $deserialized->getLocationsString();

		$locations = $deserialized->getLocations();

		$this->assertEquals(4, count($location_strings), 'Unexpected number of location strings');

		$this->assertEquals(array(
			"Dushanbe",
		    "Kyrgyzstan",
		    "Europe",
      		"Yuzhniy Ferganskiy Kanal Imeni Andreyeva"), $location_strings);


		$this->assertEquals(count($location_strings), count($locations));

		$this->assertContainsOnlyInstancesOf('KlinkGeoJsonFeature', $locations);

		$first = $locations[0];

		$this->assertEquals('Feature', $first->getType());

		$this->assertInstanceOf('KlinkGeoJsonGeometry', $first->getGeometry());

		if($first->getGeometry()->getType() === KlinkGeoJsonGeometry::TYPE_POINT){
			$this->assertEquals(2, count($first->getGeometry()->getCoordinates()), 'Point geometry should have directly the coordinates in a single array');
		}
		else {
			$this->assertEquals(1, count($first->getGeometry()->getCoordinates()));	
		}		

		$this->assertEquals(array(
		    'name' => 'Dushanbe',
		    'geonameID' => '8406256',
		    'countryCode' => 'KG'), $first->getProperties());


	}

	/**
	 * @dataProvider validKlinkIdComponentDataprovider
	 */
	public function testBuildKlinkId($institutionID, $localDocumentID, $expectedId){
		

		$id = KlinkDocumentDescriptor::buildKlinkId($institutionID, $localDocumentID);
		$this->assertEquals($expectedId, $id);
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @dataProvider invalidKlinkIdComponentDataprovider
	 */
	public function testBuildKlinkIdWithInvalidData($institutionID, $localDocumentID){
		
		KlinkDocumentDescriptor::buildKlinkId($institutionID, $localDocumentID);
	}

	/**
	 * @dataProvider createTestDataprovider
	 */
	public function testCreateMethod($inst, $doc, $hash, $title, $mimeType, $docUrl, $thumbUrl, $owner, $uploader, $visibility, $creationDate){
		$descr =  KlinkDocumentDescriptor::create(
                $inst, 
                $doc, 
                $hash, 
                $title, 
                $mimeType,
                $docUrl,
                $thumbUrl,
                $uploader,
                $owner,
                $visibility,
				$creationDate);
		$this->assertEquals($inst, $descr->getInstitutionID());
		$this->assertEquals($doc, $descr->getLocalDocumentID());
		$this->assertEquals($inst . '-' . $doc, $descr->getKlinkId());
		$this->assertEquals($hash, $descr->getHash());
		$this->assertEquals($title, $descr->getTitle());
		$this->assertEquals($docUrl, $descr->getDocumentURI());
		$this->assertEquals(array(), $descr->getLocationsString());
		$this->assertEmpty($descr->getAuthors());
		$this->assertEmpty($descr->getAbstract());
		$this->assertNull($descr->getLanguage());
		$this->assertEmpty($descr->getProjects());
		$this->assertEquals($mimeType, $descr->getMimeType());
		$this->assertEquals(KlinkDocumentUtils::documentTypeFromMimeType($mimeType), $descr->getDocumentType());
		$this->assertEquals($thumbUrl, $descr->getThumbnailURI());
		$this->assertEquals($uploader, $descr->getUserUploader());
		$this->assertEquals($owner, $descr->getUserOwner());
		
		if(is_null($visibility)){
			$this->assertEquals(KlinkVisibilityType::KLINK_PUBLIC, $descr->getVisibility());
		}
		else {
			$this->assertEquals($visibility, $descr->getVisibility());
		}
		if(is_null($creationDate)){
			$this->assertNotEmpty($descr->getCreationDate());
		}
		else {
			$this->assertEquals($creationDate, $descr->getCreationDate());
		}
	}
}
