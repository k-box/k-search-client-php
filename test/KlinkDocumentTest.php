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

	  	ini_set("display_errors", 1);
		ini_set("track_errors", 1);

		error_reporting(E_ALL);

	}

	public function tearDown(){
		
		if(!is_null($this->stream) && @get_resource_type($this->stream) === 'stream'){
			fclose($this->stream);
		}
		
		$path = __DIR__ . '/temporary_document.txt';
		if(is_file($path)){
			unlink($path);
		}
		
		// unlink(self::getFilePath());

	}

	public function emptyValueDataprovider(){
		return [
			[''],
			['    '],
			[null],
			[array()],
			[new \stdClass],
		];
	}

	function stringContentDataprovider()
    {
        $descriptor = $this->createKlinkDocumentDescriptor();

        return array(
            'hello-data' => array($descriptor, 'hello data'),
            'html-data' => array($descriptor, 'hello data. <strong>This is <a href="http://html.html">HTML</a></strong>'),
			'1b'   => array($descriptor, '1'),
			'1K'   => array($descriptor, str_repeat('1', 1000) ),
			'10K'  => array($descriptor, str_repeat('1', 1000 * 10)),
			'100K' => array($descriptor, str_repeat('1', 1000 * 100)),
			'1M'   => array($descriptor, str_repeat('1', 1000 * 1000)),
        );
    }

	private function createKlinkDocumentDescriptor(){
		return KlinkDocumentDescriptor::create(
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
	}


 	public function testDocumentMethods(){
		 
		 $descriptor = $this->createKlinkDocumentDescriptor();
		 
		 $data = 'hello'; 
		 		 
		 $document = new KlinkDocument($descriptor, $data);
		 
		 $returned_descr = $document->getDescriptor();
		 
		 $returned_data = $document->getOriginalDocumentData();
		 
		 $this->assertEquals($data, $returned_data);
		 $this->assertEquals($descriptor, $returned_descr);
		 
	 }
	 
	 /**
	  * Retrieve a stream from a KlinkDocument initialized with a string
	  * @dataProvider stringContentDataprovider
	  */ 
	 public function testGetStringContentAsStream( $descriptor, $data ){
		 
		 $document = new KlinkDocument($descriptor, $data);
		 
		 $this->stream = $document->getDocumentStream();
		 
		 $this->assertTrue( is_resource($this->stream) );
		 $this->assertEquals( 'stream', @get_resource_type($this->stream) );
		 
		 $this->assertEquals( $data, stream_get_contents($this->stream), 'plain content check');
		 
		 fclose($this->stream);
		 
	 }

	 /**
	  * 
	  * @dataProvider stringContentDataprovider
	  */ 
	 public function testGetStringContentAsString($descriptor, $original_data){
		 
		 $expected_data = base64_encode( $original_data );

		 $document = new KlinkDocument($descriptor, $original_data);
		 
		 $data = $document->getDocumentData();
		 
		 $this->assertTrue( is_string($data) );
		 
		 $this->assertEquals( $expected_data, $data, 'base64 data check');
		 
	 }
	 
	 /**
	  * Retrieve a base64 stream from a KlinkDocument initialized with a string
	  * @dataProvider stringContentDataprovider
	  */ 
	 public function testGetStringContentAsBase64Stream($descriptor, $original_data){

		 $document = new KlinkDocument($descriptor, $original_data);
		 
		 $this->stream = $document->getDocumentBase64Stream();
		 
		 $this->assertTrue( is_resource($this->stream) );
		 $this->assertEquals( 'stream', @get_resource_type($this->stream) );
		 
		 $this->assertEquals( base64_encode($original_data), stream_get_contents($this->stream), 'base64 check');
		 
		 fclose($this->stream);
		 
	 }
	 
	 public function testGetStreamContentAsBase64String(){
		 
		 $descriptor = $this->createKlinkDocumentDescriptor();
		 
		 $this->stream = fopen('data://text/plain,hello', 'r');
		 
		 
		 $document = new KlinkDocument($descriptor, $this->stream);
		 
		 $data = $document->getDocumentData();
		 
		 $this->assertTrue( is_string($data) );
		 
		 $this->assertEquals( 'hello', base64_decode($data), 'string from stream as document content, check equal to base64_decode');
		 $this->assertEquals( base64_encode('hello'), $data, 'string from stream as document content, check equal to base64_encode');
		 
		 fclose($this->stream);
		 
	 }


    function getFileContentDataprovider()
    {
        $descriptor = $this->createKlinkDocumentDescriptor();

        return array(
            'hello-data' => array($descriptor, 'hello data'),
            'empty'      => array($descriptor, ''),
            'null'       => array($descriptor, null),
			'1b'   => array($descriptor, '1'),
			'1K'   => array($descriptor, str_repeat('1', 1000) ),
			'10K'  => array($descriptor, str_repeat('1', 1000 * 10)),
			'100K' => array($descriptor, str_repeat('1', 1000 * 100)),
			'1M'   => array($descriptor, str_repeat('1', 1000 * 1000)),
			'10M'   => array($descriptor, str_repeat('1', 1000 * 10000)),
        );
    }

    /**
     * @param KlinkDocumentDescriptor $descriptor
     * @param string                  $data
     *
     * @dataProvider getFileContentDataprovider
     */
    public function testGetFileContent($descriptor, $data)
    {
        $file_path = __DIR__ . '/temporary_document.txt';
        file_put_contents($file_path, $data);
		 
		$document = new KlinkDocument($descriptor, $file_path);
		
		$this->assertEquals($file_path, $document->getOriginalDocumentData());
		
		$this->assertTrue( $document->isFile() );
		
		$doc_data = $document->getDocumentData();
		
		$this->assertTrue( is_string($doc_data) );
		$this->assertEquals( base64_encode($data), $doc_data, 'getData as string base64');
		
		$this->stream = $document->getDocumentStream();
		
		$this->assertTrue( is_resource($this->stream) );
		$this->assertEquals( 'stream', @get_resource_type($this->stream) );
		$this->assertEquals( $data, stream_get_contents($this->stream), 'Content as stream');
		
		
		$this->stream = $document->getDocumentBase64Stream();
		
		$this->assertTrue( is_resource($this->stream) );
		$this->assertEquals( 'stream', @get_resource_type($this->stream) );
		$this->assertEquals( base64_encode($data), stream_get_contents($this->stream), 'Content as base64 stream');
		
		fclose($this->stream);
		 
	 }


	 public function testGetFileContentFromStream(){
		 
		 $descriptor = $this->createKlinkDocumentDescriptor();
		 
		 $file_path = __DIR__ . '/temporary_document.txt';
		 $data = 'hello data';
		 file_put_contents($file_path, $data);
		 
		 $file_stream = fopen($file_path, 'r');
		 
		 $document = new KlinkDocument($descriptor, $file_stream);
		 
		 $this->assertTrue( is_resource($document->getOriginalDocumentData()) );
		 $this->assertEquals( 'stream', @get_resource_type($document->getOriginalDocumentData()) );
		 
		 $this->assertFalse( $document->isFile() );
		 
		 $doc_data = $document->getDocumentData();
		 
		 $this->assertTrue( is_string($doc_data) );
		 $this->assertEquals( base64_encode($data), $doc_data, 'getData as string base64');
		 
		 $this->stream = $document->getDocumentStream();
		 
		 $this->assertTrue( is_resource($this->stream) );
		 $this->assertEquals( 'stream', @get_resource_type($this->stream) );
		 $this->assertEquals( $data, stream_get_contents($this->stream), 'Content as stream');
		 
		 
		 $this->stream = $document->getDocumentBase64Stream();
		 
		 $this->assertTrue( is_resource($this->stream) );
		 $this->assertEquals( 'stream', @get_resource_type($this->stream) );
		 $this->assertEquals( base64_encode($data), stream_get_contents($this->stream), 'Content as base64 stream');
		 
		 fclose($this->stream);
		 
	 }
	 
	 /**
	  * @expectedException UnexpectedValueException
	  */
	 public function testStreamClosedExceptionOnGetDocumentStream(){
		 
		 $descriptor = $this->createKlinkDocumentDescriptor();
		 
		 $this->stream = fopen('data://text/plain,hello', 'r');
		 
		 
		 $document = new KlinkDocument($descriptor, $this->stream);
		 
		 fclose($this->stream);
		 
		 $data = $document->getDocumentStream();
		 
	 }
	 
	 /**
	  * @expectedException UnexpectedValueException
	  */
	 public function testStreamClosedExceptionOnGetDocumentBase64Stream(){
		 
		 $descriptor = $this->createKlinkDocumentDescriptor();
		 
		 $this->stream = fopen('data://text/plain,hello', 'r');
		 
		 
		 $document = new KlinkDocument($descriptor, $this->stream);
		 
		 fclose($this->stream);
		 
		 $data = $document->getDocumentBase64Stream();
		 
	 }

	 /**
	  * @dataProvider emptyValueDataprovider
	  */
	 public function testIsFileWithEmptyData($data){

		 $descriptor = $this->createKlinkDocumentDescriptor();

		 $document = new KlinkDocument($descriptor, $data);

		 $this->assertFalse( $document->isFile() );

	 }
	 
}