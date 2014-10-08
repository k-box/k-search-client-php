<?php

/**
* 
*/
class TestBodyResponse
{
	
	function __construct($n = '', $s = '', $a = '')
	{
		$this->name = $n;

		$this->surname = $s;

		$this->address = $a;
		
	}

	/**
     * 
     * @var string
     */
	public $name;

	/**
     * 
     * @var string
     */
	public $surname;

	/**
     * 
     * @var string
     */
	public $address;

	/**
     * 
     * @var string
     */
	public $data = null;

	public $args = null;

	public $files = null;

	public $form = null;

	public $headers = null;

	public $json = null;

	public $origin = null;

	public $url = null;
}