<?php


use Klink\Network\KlinkHttp;

/**
* Test the KlinkHttp Class for basic functionality
*/
class HttpClassTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	    $this->http = new KlinkHttp('http://localhost/');
	}

	// public function inputNumbers()
 //  {
 //    return [
 //      [2, 2, 4],
 //      [2.5, 2.5, 5]
 //    ];
 //  }
	
 //  /**
 //   * @dataProvider inputNumbers
 //   */
 //  public function testCanAddNumbers($x, $y, $sum)
 //  {
 //    $this->assertEquals($sum, $this->calculator->add($x, $y));
 //  }

 //  /**
 //    * @expectedException InvalidArgumentException
 //    */
 //  public function testThrowsExceptionIfNonNumberIsPassed()
 //  {
 //    $calc = new Calculator;
 //    $calc->add('a', 'b');
 //  }
	public function testHttpGet()
	{

		$url = 'http://www.google.it';
		
		$result = $this->http->get($url);


		print_r($result);

		$this->assertTrue(true, 'message');
	}
}