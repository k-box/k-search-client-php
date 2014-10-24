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
		  [null, null, array()],
		  ['null',null, array()],
		  ['', null, array()],
		  [' ', null, array()],
		  ['K', 'KA', array()],
		  ['K', 'KA', array(0)],
		  ['K', 'KA', array('0')],
		  ['K', 'KA', array('null')],
		  ['K', 'KA', array(null)],
		  ['K', 'KA', array('key' => 'value')],
		  ['K', 'KA', array(new KlinkAuthentication( 'http://klink-experim.cloudapp.net:14000/kcore/', 'testUser', 'testPass' ), 'a string' )],
		  ['K', 'KA', array(null, new KlinkAuthentication( 'http://klink-experim.cloudapp.net:14000/kcore/', 'testUser', 'testPass' ) )],
		];
	}


	/**
	 * @dataProvider inputNoCorrect
	 * @expectedException InvalidArgumentException
	 */
	public function testConfigurationClassCreationError($instid, $adapter, $conf)
	{

		$result = new KlinkConfiguration($instid, $adapter, $conf);

	}
}