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
			['1234567890123456789012345678901234567890123456'],
			['911'],
			["1.234.567.8901"],
			["1/234/567/8901"],
			["12345678901"],
			["not a phone number"],
			['http://base'],
			[''],
			[null],
			['iusbdnjudsudu@sidud'],
			['a-sds.com'],

		];
	}

	public function phoneNumbersInput_VALID()
	{
		return [

			['(+351) 282 43 50 50'],
			['90191919908'],
			['555-8909'],
			['001 6867684'],
			['001 6867684x1'],
			['1 (234) 567-8901'],
			['1-234-567-8901 x1234'],
			['1-234-567-8901 ext1234'],
			['1-234 567.89/01 ext.1234'],
			['1(234)5678901x1234'],
			['(123)8575973'],
			['(0055)(123)8575973'],
			['02-64487851'],
			['+39 02-64487851'],
			["1-234-567-8901"],
			["1-234-567-8901 x1234"],
			["1-234-567-8901 ext1234"],
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
	 * @dataProvider phoneNumbersInput_VALID
	 */
	public function testIsPhoneValidWithValidData( $number ){

		KlinkHelpers::is_valid_phonenumber( $number, "number" );

		$this->assertTrue(true, 'invalid');

	}


}