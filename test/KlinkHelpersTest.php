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
		return array(
			array('mob 07777 777777'),
			array('1234 567 890 after 5pm'),
			array('john smith'),
			array('(empty)'),
			array("1/234/567/8901"),
			array("not a phone number"),
			array('http://base'),
			array(''),
			array(null),
			array('iusbdnjudsudu@sidud'),
			array('a-sds.com'),
			array('1-234-567-8901 ext1234'),
			array('1-234 567.89/01 ext.1234'),
		);
	}

	public function phoneNumbersInput_VALID()
	{
		return array(

			array('(+351) 282 43 50 50'),
			array('90191919908'),
			array("1.234.567.8901"),
			array('555-8909'),
			array('001 6867684'),
			array('001 6867684x1'),
			array('1 (234) 567-8901'),
			array('1-234-567-8901 x1234'),
			array('1(234)5678901x1234'),
			array('(123)8575973'),
			array('(0055)(123)8575973'),
			array('02-64487851'),
			array('+39 02-64487851'),
			array("1-234-567-8901"),
			array("1-234-567-8901 x1234"),
			array("1 (234) 567-8901"),
			array('12 1234 123 1 x1111'),
			array('12 12 12 12 12'),
			array('12 1 1234 123456 x12345'),
			array('+12 1234 1234'),
			array('+12 12 12 1234'),
			array('+12 1234 5678'),
			array('+12 12345678'),

		);
	}

	public function mailInputs_INVALID(){
		return array(
		  array('http://base'),
		  array(''),
		  array(null),
		  array('iusbdnjudsudu@sidud'),
		  array('a-sds.com'),
		);
	}

	public function mailInputs_VALID(){
		return array(
		  array('io@me.com'),
		  array('io.dudua@meiubyd.com'),
		  array('io-778@me.com'),
		);
	}

	public function sanitize_inputs(){
		return array(
		  array('io@me.com', 'io@me.com'),
		  array('http://ciao.com/', 'http://ciao.com/'),
		  array('#bada55', '#bada55'),
		  array('<b>string with html</b>', 'string with html'),
		  array('"quotes"', '&#34;quotes&#34;'),
		  array('<script>alert(\'\');</script>Is there a script?', 'alert(&#39;&#39;);Is there a script?'),
		);
	}


	public function invalid_url()
	{
		return array(
		  array(null),
		  array('null'),
		  array(''),
		  array(' '),
		  array(0),
		  array(':/ciao.pinco'),
		  array('//www.example.co'),
		  array('ciao'),
		  array('s'),
		  array('http://'),
		  array('http://.'),
		  array('http://..'),
		  array('http://../'),
		  array('http://?'),
		  array('http://??'),
		  array('http://??/'),
		  array('http://#'),
		  array('http://##'),
		  array('http://##/'),
		  array('http://foo.bar?q=Spaces should be encoded'),
		  array('//'),
		  array('//a'),
		  array('///a'),
		  array('///'),
		  array('http:///a'),
		  array('foo.com'),
		  array('rdar://1234'),
		  array('h://test'),
		  array('http:// shouldfail.com'),
		  array(':// should fail'),
		  array('http://foo.bar/foo(bar)baz quux'),
		  array('ftps://foo.bar/'),
		  array('http://-error-.invalid/'),
		  array('http://-a.b.co'),
		  array('http://a.b-.co'),
		  array('http://0.0.0.0'),
		  array('http://10.1.1.0'),
		  array('http://10.1.1.255'),
		  array('http://224.1.1.1'),
		  array('http://1.1.1.1.1'),
		  array('http://123.123.123'),
		  array('http://3628126748'),
		  array('http://.www.foo.bar/'),
		  array('http://www.foo.bar./'),
		  array('http://.www.foo.bar./'),
		  array('http://10.1.1.1'),
		  array('http//www.example.co'),
		  array('http//example.co'),
		  array('http//git.io/'),
		  array('http//t.co/'),
		);
	}

	public function valid_url()
	{
		return array(
		  array('https://ciao.pinco'),
		  array('http://www.example.co'),
		  array('http://example.co'),
		  array('http://git.io/'),
		  array('http://t.co/'),
		  array('http://localhost/'),
		  array('http://127.0.0.1/'),
		  array('http://foo.com/blah_blah'),
		  array('http://foo.com/blah_blah/'),
		  array('http://foo.com/blah_blah_(wikipedia)'),
		  array('http://foo.com/blah_blah_(wikipedia)_(again)'),
		  array('http://www.example.com/wpstyle/?p=364'),
		  array('https://www.example.com/foo/?bar=baz&inga=42&quux'),
		  array('http://✪df.ws/123'),
		  array('http://userid:password@example.com:8080'),
		  array('http://userid:password@example.com:8080/'),
		  array('http://userid@example.com'),
		  array('http://userid@example.com/'),
		  array('http://userid@example.com:8080'),
		  array('http://userid@example.com:8080/'),
		  array('http://userid:password@example.com'),
		  array('http://userid:password@example.com/'),
		  array('http://142.42.1.1/'),
		  array('http://142.42.1.1:8080/'),
		  array('http://➡.ws/䨹'),
		  array('http://⌘.ws'),
		  array('http://⌘.ws/'),
		  array('http://foo.com/blah_(wikipedia)#cite-1'),
		  array('http://foo.com/blah_(wikipedia)_blah#cite-1'),
		  array('http://foo.com/unicode_(✪)_in_parens'),
		  array('http://foo.com/(something)?after=parens'),
		  array('http://☺.damowmow.com/'),
		  array('http://code.google.com/events/#&product=browser'),
		  array('http://j.mp'),
		  array('ftp://foo.bar/baz'),
		  array('http://foo.bar/?q=Test%20URL-encoded%20stuff'),
		  array('http://مثال.إختبار'),
		  array('http://例子.测试'),
		  array('http://उदाहरण.परीक्षा'),
		  array('http://-.~_!$&\'()*+,;=:%40:80%2f::::::@example.com'),
		  array('http://1337.net'),
		  array('http://a.b-c.de'),
		  array('http://223.255.255.254'),
		);
	}


	public function camel_case_to_unserscore()
	{
		return array(
		  array('camelCase', 'camel_case'),
		  array('StartWithACamel', 'start_with_a_camel'),
		  
		);
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

	/**
	 * @dataProvider camel_case_to_unserscore
	 */
	public function testToUnderscoreCase($input, $expected)
	{
		$converted = KlinkHelpers::to_underscore_case($input);

		$this->assertEquals($expected, $converted);
	}

}