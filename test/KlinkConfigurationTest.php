<?php

/**
* Test the KlinkConfiguration Class for correct parameter handling
*/
class KlinkConfigurationTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

	  	
	}

	public function inputNoCorrect()
	{
		return [
		  [null, array()],
		  ['null', array()],
		  ['', array()],
		  [' ', array()],
		  ['K', array()],
		  ['K', array(0)],
		  ['K', array('0')],
		  ['K', array('null')],
		  ['K', array(null)],
		  ['K', array('key' => 'value')],
		  ['K', array(new KlinkAuthentication( 'http://klink-experim.cloudapp.net:14000/kcore/', 'testUser', 'testPass' ), 'a string' )],
		  ['K', array(null, new KlinkAuthentication( 'http://klink-experim.cloudapp.net:14000/kcore/', 'testUser', 'testPass' ) )],
		];
	}


	/**
	 * @dataProvider inputNoCorrect
	 * @expectedException InvalidArgumentException
	 */
	public function testConfigurationClassCreationError($instid, $conf)
	{

		$result = new KlinkConfiguration($instid, $conf);

	}
}