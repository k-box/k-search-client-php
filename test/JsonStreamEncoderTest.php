<?php

use Seld\JsonLint\JsonParser;

class JsonStreamEncoderTest extends PHPUnit_Framework_TestCase
{

    private $stream = null;
    private $secondStream = null;

	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
        ini_set('memory_limit', '-1'); // big file, heavy strings, 128M of RAM are not enough
        ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        
        $brochure_file_path = __DIR__ . '/Brochure.pdf';
        
        $client = new GuzzleHttp\Client();
        
        if(!is_file($brochure_file_path)){
            $client->request('GET', 'https://build.klink.asia/Brochure.pdf', ['sink' => $brochure_file_path]);
        }
	}


    public function tearDown(){

        if(!is_null($this->stream) && @get_resource_type($this->stream) === 'stream'){
			fclose($this->stream);
		}

        if(!is_null($this->secondStream) && @get_resource_type($this->secondStream) === 'stream'){
			fclose($this->secondStream);
		}
		
		$path = __DIR__ . '/temporary_document.txt';
		if(is_file($path)){
			unlink($path);
		}

	}
    
    /**
     * Some data to be encoded for testing if JsonStreamEncoder creates a correct JSON
     */
    public function input_for_encoding(){

		$inputs = array(
			[true],
			[false],
			[null],
			[120],
			["0"],
			[1],
			['a lonely string'],
			[[]],
			[[
                'document' => 'an array element'
            ]],
            [new TestBodyResponse('name')],
            [new TestBodyResponse('name', 'surname')],
            [new TestBodyResponse('name', 'surname', 'address')],
            [KlinkGeoJsonGeometry::createPoint(40.24934, 74.33804)],
            [new KlinkDocumentDescriptor()],
            [KlinkDocumentDescriptor::create(
                'inst', 
                'ainsma', 
                'iabdubddubdusbdusbdusbdsu', 
                'document title', 
                'application/pdf',
                'https://something.com/doc',
                'https://something.com/thumb',
                'owner <owner@something.com>',
                'uploaded <uploader@something.com>',
                'private')],
		);

		 return $inputs;
	}
    
    /**
     * test using stream for base64 encode and encode using JsonStreamEncoder
     */
    public function testEncodeFullStream()
	{
        
        $file = __DIR__ . '/Brochure.pdf';
        
        $file_encoded = KlinkDocumentUtils::getBase64Stream( $file ); // read the file content as base64 stream

        $file_hash = hash_file('sha512', $file);


        $arr = [
            'document' => $file_encoded
        ];
		
        $this->stream = tmpfile();
        
        $encoder = new JsonStreamEncoder($this->stream);
        
        $encoder->encode($arr);
        
        $parser = new JsonParser();
        fseek($this->stream, 0);
        $res = $parser->lint(stream_get_contents($this->stream));
        
        
        $this->assertNull($res);

        fseek($this->stream, 0);

        $decoded = json_decode(stream_get_contents($this->stream), false);

        $this->assertTrue(property_exists($decoded, 'document'));

        $base64_free = base64_decode($decoded->document);

        $this->assertEquals($file_hash, hash('sha512', $base64_free), 'Hash not equals' );

		fclose($this->stream);

	}


    function getFileContentDataprovider()
    {
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

        return array(
            'hello-data' => array($descriptor, 'hello data'),
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
    public function testEncodeFileContentOfVariousSizes($descriptor, $data)
    {
        $filter = 'convert.base64-encode';
        $file_path = __DIR__ . '/temporary_document.txt';
        file_put_contents($file_path, $data);

        $hash = hash('sha512', $data);

        $document = new KlinkDocument($descriptor, $file_path);
		
        $encoder = new JsonStreamEncoder();
 
		$encoder->encode($array = array(
			'descriptor' => $document->getDescriptor(),
			'documentData' => $document->getDocumentBase64Stream(),
		));

        $this->stream = $encoder->getJsonStream();

        $parser = new JsonParser();
        fseek($this->stream, 0);
        $res = $parser->lint(stream_get_contents($this->stream));

        $this->assertNull($res);

        fseek($this->stream, 0);

        $decoded = json_decode(stream_get_contents($this->stream), false);

        $this->assertTrue(property_exists($decoded, 'documentData'));

        $base64_free = base64_decode($decoded->documentData);

        $this->assertEquals(strlen($data), strlen($base64_free), 'Length of data and decoded version after JSON encoding is not equal' );

        $this->assertEquals($hash, hash('sha512', $base64_free), 'Hash not equals' );

		fclose($this->stream);
		 
	 }

    
    /**
     * Test if the exception UnexpectedValueException is thrown when I use a closed stream inside the object I want to encode 
     * @expectedException UnexpectedValueException
     */
    public function testEncodeWithStreamClosed()
	{
        $start = memory_get_usage();
        
        $filter = 'convert.base64-encode';
        $file = __DIR__ . '/Brochure.pdf';
        
        $h = fopen('php://filter/read=' . $filter . '/resource=' . $file,'r'); 
        
        $arr = [
            'document' => $h
        ];
        
        fclose($h);

        $temp = tmpfile();
        
        $encoder = new JsonStreamEncoder($temp);
        
        $encoder->encode($arr);

	}
    
    /**
     * Test if the JsonStreamEncoder encodes arrays, primitive types and objects in 
     * a valid json string and in the same format as json_encode
     *
     * @dataProvider input_for_encoding
     */
    public function testEncodePrimitiveTypes( $arr )
	{

        $encoder = new JsonStreamEncoder();
        
        $encoder->encode($arr);
        
        $parser = new JsonParser();
        
        $encoded_json = stream_get_contents($encoder->getJsonStream());
        
        $encoder->closeJsonStream();
        
        // var_dump(json_encode($arr)); 
        // var_dump($encoded_json);
        
        $res = $parser->lint( $encoded_json );
        
        $this->assertNull($res); // no syntax error in the JSON
        
        $this->assertEquals(json_encode($arr), $encoded_json);

	}
    
    /**
     * Check if when using a tmpfile for the json stream, the temporary file is deleted after stream close 
    */
    public function testEncoderTempFiles(){
        
        $s = tmpfile();
        
        $metadata = stream_get_meta_data($s);
        
        $uri = $metadata['uri'];
        
		$encoder = new JsonStreamEncoder($s);
        
        $encoder->encode('hello');
        
        $encoder->closeJsonStream();
        
        $this->assertFalse(is_file($uri));
        
	}
    
    public function testEncoderSelfGeneratedTempFiles(){
		
		$encoder = new JsonStreamEncoder();
        
        $metadata = stream_get_meta_data($encoder->getJsonStream());
        
        $uri = $metadata['uri'];
        
        $encoder->encode('hello');
        
        $encoder->closeJsonStream();
        
        $this->assertFalse(is_file($uri));
        
	}

}
