<?php

/**
 * Define the health check details structure
 * 
 * @package Klink
 */
final class KlinkHealthCheck
{
	


	/**
	 * The original query given at API invocation
	 * @var string
	 */

	public $checkName;
	
	/**
		@var string
	*/
	public $message;
	
	/**
		@var int
	*/
	public $status;
	
	/**
		@var string
	*/
	public $status_name;
	
	/**
		@var string
	*/
	public $service_id;

//	/**
//	 * getQuery
//	 * @return string
//	 */
//	public function getStatus() {
//		return $this->status;
//	}
//	
//	public function getMe() {
//		return $this->status;
//	}

	

	


	/**
	 * @internal no one can create an instance of this class and remain alive
	 */
	function __construct()
	{
		
	}

}