<?php


class JsonStreamEncoderTest extends PHPUnit_Framework_TestCase
{


	public function setUp()
	{
	  	date_default_timezone_set('Europe/Rome');
        ini_set('memory_limit', '-1');
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
        fclose($temp);
        
        $end = memory_get_usage();
        var_dump('start ' . ($start/1024) . ' KB');
        var_dump('end ' . ($end/1024) . ' KB');
        var_dump('diff ' . (($end - $start)/1024) . ' KB');
        var_dump('Peak memory usage ' . (memory_get_peak_usage(true)/1024) . ' KB');
        
        
        // TODO: test if JSON linting says it is valid

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
		
        var_dump($arr);
        
        
        
        $temp = tmpfile();
        
        $encoder = new JsonStreamEncoder($temp);
        
        $encoder->encode($arr);
        
        fseek($temp, 0);
        var_dump('Output truncated to 1024 bytes');
        var_dump(fread($temp, 1024));
        // var_dump(stream_get_contents($temp)); // Use this on a green console if you want to experience the Matrix
        fclose($temp);
        
        
        $end = memory_get_usage();
        var_dump('start ' . ($start/1024) . ' KB');
        var_dump('end ' . ($end/1024) . ' KB');
        var_dump('diff ' . (($end - $start)/1024) . ' KB');
        var_dump('Peak memory usage ' . (memory_get_peak_usage(true)/1024) . ' KB');
        
        // TODO: test if JSON linting says it is valid

	}

}