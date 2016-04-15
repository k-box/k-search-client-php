<?php

/**
* Test the KlinkDocument class for basic functionality
*/
class KlinkDocumentTest extends PHPUnit_Framework_TestCase 
{

	// public static $test_pdf_file_path = null;

	// public static function setUpBeforeClass()
 //    {
 //        KlinkDocumentUtilsTest::$test_pdf_file_path = __DIR__ . '/test.pdf';
 //    }
 	
	private $stream = null;
	 
	public function setUp()
	{
		date_default_timezone_set('Europe/Rome');
		  
		// KlinkDocumentDescriptor::create(
        //         'inst', 
        //         'ainsma', 
        //         'iabdubddubdusbdusbdusbdsu', 
        //         'document title', 
        //         'application/pdf',
        //         'https://something.com/doc',
        //         'https://something.com/thumb',
        //         'owner <owner@something.com>',
        //         'uploaded <uploader@something.com>',
        //         'private')

	  	ini_set("display_errors", 1);
		ini_set("track_errors", 1);
		// ini_set("html_errors", 1);
		error_reporting(E_ALL);

		

	}

	// public static function getFilePath(){
	// 	return __DIR__ . '/test.pdf';
	// }

	// public static function getRussianFilePath(){
	// 	return __DIR__ . '/идетельсто.pdf';
	// }

	public function tearDown(){
		
		if(!is_null($this->stream) && @get_resource_type($this->stream) === 'stream'){
			fclose($this->stream);
		}
		
		// unlink(self::getFilePath());

	}

 	/**
 	 * @dataProvider fileInput
 	 */
 	public function testGetMimeType( $expected, $file )
 	{

 		$actual = KlinkDocumentUtils::get_mime( $file );

 		$this->assertEquals( $expected, $actual);
 		
 	}


 	public function testDocumentMethods(){
		 
		 $descriptor = KlinkDocumentDescriptor::create(
                'inst', 
                'ainsma', 
                'iabdubddubdusbdusbdusbdsu', 
                'document title', 
                'application/pdf',
                'https://something.com/doc',
                'https://something.com/thumb',
                'owner <owner@something.com>',
                'uploaded <uploader@something.com>',
                'private');
		 
		 $data = 'hello'; 
		 		 
		 $document = new KlinkDocument($descriptor, $data);
		 
		 $returned_descr = $document->getDescriptor();
		 
		 $returned_data = $document->getOriginalDocumentData();
		 
		 $this->assertEquals($data, $returned_data);
		 $this->assertEquals($descriptor, $returned_descr);
		 
	 }
	 
	 /**
	  * Retrieve a stream from a KlinkDocument initialized with a string
	  */ 
	 public function testGetStringContentAsStream(){
		 
		 $descriptor = KlinkDocumentDescriptor::create(
                'inst', 
                'ainsma', 
                'iabdubddubdusbdusbdusbdsu', 
                'document title', 
                'application/pdf',
                'https://something.com/doc',
                'https://something.com/thumb',
                'owner <owner@something.com>',
                'uploaded <uploader@something.com>',
                'private');
		 
		 $document = new KlinkDocument($descriptor, 'hello');
		 
		 $this->stream = $document->getDocumentStream();
		 
		 $this->assertTrue( is_resource($this->stream) );
		 $this->assertEquals( 'stream', @get_resource_type($this->stream) );
		 
		 $this->assertEquals( 'hello', stream_get_contents($this->stream), 'plain content check');
		 
		 fclose($this->stream);
		 
	 }
	 
	 /**
	  * Retrieve a base64 stream from a KlinkDocument initialized with a string
	  */ 
	 public function testGetStringContentAsBase64Stream(){
		 
		 $descriptor = KlinkDocumentDescriptor::create(
                'inst', 
                'ainsma', 
                'iabdubddubdusbdusbdusbdsu', 
                'document title', 
                'application/pdf',
                'https://something.com/doc',
                'https://something.com/thumb',
                'owner <owner@something.com>',
                'uploaded <uploader@something.com>',
                'private');
		 
		 $document = new KlinkDocument($descriptor, 'hello');
		 
		 $this->stream = $document->getDocumentBase64Stream();
		 
		 $this->assertTrue( is_resource($this->stream) );
		 $this->assertEquals( 'stream', @get_resource_type($this->stream) );
		 
		 $this->assertEquals( base64_encode('hello'), stream_get_contents($this->stream), 'base64 check');
		 
		 fclose($this->stream);
		 
	 }
	 
	 public function testGetStreamContentAsBase64String(){
		 
		 $descriptor = KlinkDocumentDescriptor::create(
                'inst', 
                'ainsma', 
                'iabdubddubdusbdusbdusbdsu', 
                'document title', 
                'application/pdf',
                'https://something.com/doc',
                'https://something.com/thumb',
                'owner <owner@something.com>',
                'uploaded <uploader@something.com>',
                'private');
		 
		 $this->stream = fopen('data://text/plain,hello', 'r');
		 
		 
		 $document = new KlinkDocument($descriptor, $this->stream);
		 
		 $data = $document->getDocumentData();
		 
		 $this->assertTrue( is_string($data) );
		 
		 $this->assertEquals( 'hello', base64_decode($data), 'string from stream as document content, check equal to base64_decode');
		 $this->assertEquals( base64_encode('hello'), $data, 'string from stream as document content, check equal to base64_encode');
		 
		 fclose($this->stream);
		 
	 }
	 
	 
	 // TODO: add a check for file path content that is correctly (full content) returned as base64


}