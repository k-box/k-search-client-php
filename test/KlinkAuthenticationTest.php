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
		return array(
		  array(null, null, null),
		  array('null', 'null', 'null'),
		  array('', '', ''),
		  array(' ', 'user', 'pass'),
		  array(0, 0, 0),
		  array('http://www.ciao.org', 0, 0),
		  array('http://www.ciao.org', '', 0),
		  array('http://www.ciao.org', ' ', 0),
		  array('http://www.ciao.org', 'dcd', 0),
		  array('http://www.ciao.org', '0', 0),
		  array('http://www.ciao.org', 'username', 0),
		  array(':/ciao.pinco', 'user', 'pass'),
		  array('//www.example.com/path?googleguy=googley', 'user', 'pass'),
		  array('ciao', 'user', 'pass'),
		  array('s', 'user', 'pass')
		);
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
		$this->assertEquals(\KlinkVisibilityType::KLINK_PRIVATE, $result->getTag());

	}
}