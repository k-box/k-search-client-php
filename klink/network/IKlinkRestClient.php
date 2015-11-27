<?php

use Psr\Log\LoggerAwareInterface;


/**
 * Interface that defines what a KlinkRestClient should expose
 */
interface IKlinkRestClient extends LoggerAwareInterface {
	
	/**
	 * HTTP GET
	 */
	public function get( $url, $expected_return_type, array $params = null );
	
	/**
	 * HTTP GET with expected array as response
	 */
	public function getCollection( $url, array $params = null, $expected_return_type );
	
	/**
	 * HTTP POST
	 */
	public function post( $url, $data, $expected_return_type, array $params = null );
	
	/**
	 * HTTP PUT
	 */
	public function put( $url, $data, $expected_return_type = null, array $params = null );
	
	/**
	 * HTTP DELETE
	 */
	public function delete( $url, array $params = null );

}