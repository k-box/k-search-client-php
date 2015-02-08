<?php

/**
* Test the KlinkHelpers Class for basic functionality
*/
class KlinkHelpersTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{

	  	date_default_timezone_set('America/Los_Angeles');

	}


	public function phoneNumbersInput_INVALID()
	{
		return [

			['mob 07777 777777'],
			['1234 567 890 after 5pm'],
			['john smith'],
			['(empty)'],
			["1/234/567/8901"],
			["not a phone number"],
			['http://base'],
			[''],
			[null],
			['iusbdnjudsudu@sidud'],
			['a-sds.com'],
			['1-234-567-8901 ext1234'],
			['1-234 567.89/01 ext.1234'],
		];
	}

	public function phoneNumbersInput_VALID()
	{
		return [

			['(+351) 282 43 50 50'],
			['90191919908'],
			["1.234.567.8901"],
			['555-8909'],
			['001 6867684'],
			['001 6867684x1'],
			['1 (234) 567-8901'],
			['1-234-567-8901 x1234'],
			['1(234)5678901x1234'],
			['(123)8575973'],
			['(0055)(123)8575973'],
			['02-64487851'],
			['+39 02-64487851'],
			["1-234-567-8901"],
			["1-234-567-8901 x1234"],
			["1 (234) 567-8901"],
			['12 1234 123 1 x1111'],
			['12 12 12 12 12'],
			['12 1 1234 123456 x12345'],
			['+12 1234 1234'],
			['+12 12 12 1234'],
			['+12 1234 5678'],
			['+12 12345678'],

		];
	}

	public function mailInputs_INVALID(){
		return [
		  ['http://base'],
		  [''],
		  [null],
		  ['iusbdnjudsudu@sidud'],
		  ['a-sds.com'],
		];	
	}

	public function mailInputs_VALID(){
		return [
		  ['io@me.com'],
		  ['io.dudua@meiubyd.com'],
		  ['io-778@me.com'],
		];	
	}

	public function sanitize_inputs(){
		return [
		  ['io@me.com', 'io@me.com'],
		  ['http://ciao.com/', 'http://ciao.com/'],
		  ['#bada55', '#bada55'],
		  ['<b>string with html</b>', 'string with html'],
		  ['"quotes"', '&#34;quotes&#34;'],
		  ['<script>alert(\'\');</script>Is there a script?', 'alert(&#39;&#39;);Is there a script?'],
		];	
	}


	public function invalid_url()
	{
		return [
		  [null],
		  ['null'],
		  [''],
		  [' '],
		  [0],
		  [':/ciao.pinco'],
		  ['//www.example.co'],
		  ['ciao'],
		  ['s'],
		  ['http://'],
		  ['http://.'],
		  ['http://..'],
		  ['http://../'],
		  ['http://?'],
		  ['http://??'],
		  ['http://??/'],
		  ['http://#'],
		  ['http://##'],
		  ['http://##/'],
		  ['http://foo.bar?q=Spaces should be encoded'],
		  ['//'],
		  ['//a'],
		  ['///a'],
		  ['///'],
		  ['http:///a'],
		  ['foo.com'],
		  ['rdar://1234'],
		  ['h://test'],
		  ['http:// shouldfail.com'],
		  [':// should fail'],
		  ['http://foo.bar/foo(bar)baz quux'],
		  ['ftps://foo.bar/'],
		  ['http://-error-.invalid/'],
		  ['http://-a.b.co'],
		  ['http://a.b-.co'],
		  ['http://0.0.0.0'],
		  ['http://10.1.1.0'],
		  ['http://10.1.1.255'],
		  ['http://224.1.1.1'],
		  ['http://1.1.1.1.1'],
		  ['http://123.123.123'],
		  ['http://3628126748'],
		  ['http://.www.foo.bar/'],
		  ['http://www.foo.bar./'],
		  ['http://.www.foo.bar./'],
		  ['http://10.1.1.1'],
		  ['http//www.example.co'],
		  ['http//example.co'],
		  ['http//git.io/'],
		  ['http//t.co/'],
		];
	}

	public function valid_url()
	{
		return [
		  ['https://ciao.pinco'],
		  ['http://www.example.co'],
		  ['http://example.co'],
		  ['http://git.io/'],
		  ['http://t.co/'],
		  ['http://localhost/'],
		  ['http://127.0.0.1/'],
		  ['http://foo.com/blah_blah'],
		  ['http://foo.com/blah_blah/'],
		  ['http://foo.com/blah_blah_(wikipedia)'],
		  ['http://foo.com/blah_blah_(wikipedia)_(again)'],
		  ['http://www.example.com/wpstyle/?p=364'],
		  ['https://www.example.com/foo/?bar=baz&inga=42&quux'],
		  ['http://✪df.ws/123'],
		  ['http://userid:password@example.com:8080'],
		  ['http://userid:password@example.com:8080/'],
		  ['http://userid@example.com'],
		  ['http://userid@example.com/'],
		  ['http://userid@example.com:8080'],
		  ['http://userid@example.com:8080/'],
		  ['http://userid:password@example.com'],
		  ['http://userid:password@example.com/'],
		  ['http://142.42.1.1/'],
		  ['http://142.42.1.1:8080/'],
		  ['http://➡.ws/䨹'],
		  ['http://⌘.ws'],
		  ['http://⌘.ws/'],
		  ['http://foo.com/blah_(wikipedia)#cite-1'],
		  ['http://foo.com/blah_(wikipedia)_blah#cite-1'],
		  ['http://foo.com/unicode_(✪)_in_parens'],
		  ['http://foo.com/(something)?after=parens'],
		  ['http://☺.damowmow.com/'],
		  ['http://code.google.com/events/#&product=browser'],
		  ['http://j.mp'],
		  ['ftp://foo.bar/baz'],
		  ['http://foo.bar/?q=Test%20URL-encoded%20stuff'],
		  ['http://مثال.إختبار'],
		  ['http://例子.测试'],
		  ['http://उदाहरण.परीक्षा'],
		  ['http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'],
		  ['http://1337.net'],
		  ['http://a.b-c.de'],
		  ['http://223.255.255.254'],
		];
	}
	
	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}


	/**
	 * @dataProvider mailInputs_INVALID
	 * @expectedException InvalidArgumentException
	 */
	public function testIsMailValidWithInvalidData( $mail ){

		KlinkHelpers::is_valid_mail( $mail, "mail" );

	}

	/**
	 * @dataProvider mailInputs_VALID
	 */
	public function testIsMailValidWithValidData( $mail ){

		KlinkHelpers::is_valid_mail( $mail, "mail" );

		$this->assertTrue(true, 'invalid');

	}


	/**
	 * @dataProvider phoneNumbersInput_INVALID
	 * @expectedException InvalidArgumentException
	 */
	public function testIsPhoneValidWithInvalidData( $number ){

		KlinkHelpers::is_valid_phonenumber( $number, "number" );

	}

	/**
	 * @dataProvider invalid_url
	 * @expectedException InvalidArgumentException
	 */
	public function testUrlValidationWithInvalidData( $number ){

		KlinkHelpers::is_valid_url( $number, "url" );

	}

	/**
	 * @dataProvider valid_url
	 */
	public function testUrlValidationWithValidData( $number ){

		KlinkHelpers::is_valid_url( $number, "url" );

	}


	/**
	 * @dataProvider phoneNumbersInput_VALID
	 */
	public function testIsPhoneValidWithValidData( $number ){

		KlinkHelpers::is_valid_phonenumber( $number, "number" );

		$this->assertTrue(true, 'invalid');

	}

	/**
	 * @dataProvider sanitize_inputs
	 */
	public function testSanitizeString( $string, $expected ){

		$sanitized = KlinkHelpers::sanitize_string( $string );


		$this->assertEquals($expected, $sanitized);
	}


}