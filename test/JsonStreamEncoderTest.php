<?php

use Seld\JsonLint\JsonParser;

class JsonStreamEncoderTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
        ini_set('memory_limit', '-1'); // big file, heavy strings, 128M of RAM are not enough
        
        $brochure_file_path = __DIR__ . '/Brochure.pdf';
        
        $client = new GuzzleHttp\Client();
        
        if(!is_file($brochure_file_path)){
            $client->request('GET', 'https://build.klink.asia/Brochure.pdf', ['sink' => $brochure_file_path]);
        }
	}

	
    /**
     * test the current data construction encodes with JsonStreamEncoder 
     */
	public function testEncodeWithInMemoryBase64()
	{
        $start = memory_get_usage();
        $arr = [
            'document' => base64_encode(file_get_contents( __DIR__ . '/Brochure.pdf'))
        ];
		
        $temp = tmpfile();
        
        $encoder = new JsonStreamEncoder($temp);
        
        $encoder->encode($arr);
        
        fseek($temp, 0);
        var_dump(fread($temp, 1024));
        
        
        $end = memory_get_usage();
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

}