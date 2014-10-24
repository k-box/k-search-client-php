<?php

/**
* Test the KlinkAuthentication Class for correct parameter handling
*/
class KlinkAuthenticationTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	  	
	}

	public function inputNoCorrect()
	{
		return [
		  [null, null, null],
		  ['null', 'null', 'null'],
		  ['', '', ''],
		  [' ', 'user', 'pass'],
		  [0, 0, 0],
		  ['http://www.ciao.org', 0, 0],
		  ['http://www.ciao.org', '', 0],
		  ['http://www.ciao.org', ' ', 0],
		  ['http://www.ciao.org', 'dcd', 0],
		  ['http://www.ciao.org', '0', 0],
		  ['http://www.ciao.org', 'username', 0],
		  ['http://www.ciao.org', 'username', '0'],
		  [':/ciao.pinco', 'user', 'pass'],
		  ['//www.example.com/path?googleguy=googley', 'user', 'pass'],
		  ['ciao', 'user', 'pass']
		];
	}


	/**
	 * @dataProvider inputNoCorrect
	 * @expectedException InvalidArgumentException
	 */
	public function testClassExpectedError($coreurl, $user, $pass)
	{

		$result = new KlinkAuthentication($coreurl, $user, $pass);

	}

	public function testCorrectConstruction(){

		$url = 'http://klink-experim.cloudapp.net:14000/kcore/';
		$user = 'testUser';
		$pass = 'testPass';

		$result = new KlinkAuthentication($url, $user, $pass);

		$this->assertInstanceOf('KlinkAuthentication', $result);

		$this->assertEquals($url, $result->getCore());
		$this->assertEquals($user, $result->getUsername());
		$this->assertEquals($pass, $result->getPassword());

	}
}