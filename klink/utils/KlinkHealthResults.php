<?php

/**
 * Define the health api result structure
 * 
 * @package Klink
 */
final class KlinkHealthResults
{
	


	/**
	 * The original query given at API invocation
	 * @var string
	 */

	public $globalStatus;

	/**
	 * getQuery
	 * @return string
	 */
	public function getStatus() {
		return $this->globalStatus;
	}

	
	/**
	 * The list of checks performed and the returned status information
	 * @var KlinkHealthCheck[]
	 */

	public $checks;

	/**
	 * The list of checks performed and the returned status information
	 * @return KlinkHealthCheck[]
	 */
	public function getResults() {
		return $this->checks;
	}

	


	/**
	 * @internal no one can create an instance of this class and remain alive
	 */
	function __construct()
	{
		$this->globalStatus = "OK";
		$this->checks = array();
	}

}