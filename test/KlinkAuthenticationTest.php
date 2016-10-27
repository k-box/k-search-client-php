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
			// invalid values for url, username or password
		  array(null, null, null, null, null),
		  array('null', 'null', 'null', null, null),
		  array('', '', '', null, null),
		  array(' ', 'user', 'pass', null, null),
		  array(0, 0, 0, null, null),
		  array('http://www.ciao.org', 0, 0, null, null),
		  array('http://www.ciao.org', '', 0, null, null),
		  array('http://www.ciao.org', ' ', 0, null, null),
		  array('http://www.ciao.org', 'dcd', 0, null, null),
		  array('http://www.ciao.org', '0', 0, null, null),
		  array('http://www.ciao.org', 'username', 0, null, null),
		  array(':/ciao.pinco', 'user', 'pass', null, null),
		  array('//www.example.com/path?googleguy=googley', 'user', 'pass', null, null),
		  array('ciao', 'user', 'pass', null, null),
		  array('s', 'user', 'pass', null, null),
		  // invalid values for tag or apiVersion
		  array('https://kcore.local/kcore/', 'user', 'pass', 'personal', null),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'p', null),
		  array('https://kcore.local/kcore/', 'user', 'pass', 10, null),
		  array('https://kcore.local/kcore/', 'user', 'pass', true, null),
		  array('https://kcore.local/kcore/', 'user', 'pass', false, null),
		  array('https://kcore.local/kcore/', 'user', 'pass', array(), null),
		  array('https://kcore.local/kcore/', 'user', 'pass', new stdClass, null),
		  array('https://kcore.local/kcore/', 'user', 'pass', 5.0, null),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', 'hello'),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', 'h'),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', '2.1.1.0'),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', '2'),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', 2),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', true),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', false),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', array()),
		  array('https://kcore.local/kcore/', 'user', 'pass', 'private', new stdClass),
		);
	}

	public function apiVersionInputProvider()
	{
		return array(
		  array('2.1'),
		  array('2.2'),
		);
	}


	/**
	 * @dataProvider inputNoCorrect
	 * @expectedException InvalidArgumentException
	 */
	public function testClassExpectedError($coreurl, $user, $pass, $tag, $apiVersion)
	{

		$result = new KlinkAuthentication($coreurl, $user, $pass, $tag, $apiVersion);

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
		$this->assertEquals(\KlinkCoreClient::DEFAULT_KCORE_API_VERSION, $result->getApiVersion());

	}

	/**
	 * @dataProvider apiVersionInputProvider
	 */
	public function testConstructionWithExplicitApiVersion($apiVersion){
		$url = 'http://klink-experim.cloudapp.net:14000/kcore/';
		$user = 'testUser';
		$pass = 'testPass';

		$result = new KlinkAuthentication($url, $user, $pass, \KlinkVisibilityType::KLINK_PRIVATE, $apiVersion);

		$this->assertInstanceOf('KlinkAuthentication', $result);

		$this->assertEquals($url, $result->getCore());
		$this->assertEquals($user, $result->getUsername());
		$this->assertEquals($pass, $result->getPassword());
		$this->assertEquals(\KlinkVisibilityType::KLINK_PRIVATE, $result->getTag());
		$this->assertEquals($apiVersion, $result->getApiVersion());
	}
}