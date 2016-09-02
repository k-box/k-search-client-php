<?php

use Seld\JsonLint\JsonParser;

class JsonStreamEncoderTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
        ini_set('memory_limit', '-1'); // big file, heavy strings, 128M of RAM are not enough
        ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
        
        $brochure_file_path = __DIR__ . '/Brochure.pdf';
// var_dump('setup');        
        $client = new GuzzleHttp\Client();
        
        if(!is_file($brochure_file_path)){
            $client->request('GET', 'https://build.klink.asia/Brochure.pdf', ['sink' => $brochure_file_path]);
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
     * test the current data construction encodes with JsonStreamEncoder 
     * @requires PHP 5.6.0
     * @runInSeparateProcess
     */
	public function testEncodeWithInMemoryBase64()
	{
        ini_set('memory_limit', '-1');
        
        // $start = memory_get_usage();
        $arr = [
            'document' => base64_encode(file_get_contents( __DIR__ . '/Brochure.pdf'))
        ];
		
        $temp = tmpfile();
        
        $encoder = new JsonStreamEncoder($temp);
        
        $encoder->encode($arr);
        
        $encoded_json = stream_get_contents($encoder->getJsonStream());
        
        $encoder->closeJsonStream();
        
        $parser = new JsonParser();
        
        $res = $parser->lint($encoded_json);
        
        $this->assertNull($res);

	}
    
    /**
     * test using stream for base64 encode and encode using JsonStreamEncoder
     */
    public function testEncodeFullStream()
	{
        $start = memory_get_usage();
        
        $filter = 'convert.base64-encode';
        $file = __DIR__ . '/Brochure.pdf';
        $h = fopen('php://filter/read=' . $filter . '/resource=' . $file,'r'); 
        
        $arr = [
            'document' => $h
        ];
		
        $temp = tmpfile();
        
        $encoder = new JsonStreamEncoder($temp);
        
        $encoder->encode($arr);
        
        $end = memory_get_usage();
        
        // fseek($temp, 0);
        // var_dump('Output truncated to 1024 bytes');
        // var_dump(fread($temp, 1024));
        // var_dump(stream_get_contents($temp)); // Use this on a green console if you want to experience the Matrix
        
        
        
        // to effectively use this values execute the test in its own PHP process, otherwise other processes will affect the results
        // var_dump('start ' . ($start/1024) . ' KB');
        // var_dump('end ' . ($end/1024) . ' KB');
        // var_dump('diff ' . (($end - $start)/1024) . ' KB');
        // var_dump('Peak memory usage ' . (memory_get_peak_usage(true)/1024) . ' KB');
        
        $parser = new JsonParser();
        fseek($temp, 0);
        $res = $parser->lint(stream_get_contents($temp));
        fclose($temp);
        
        $this->assertNull($res);

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
