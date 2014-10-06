<?php namespace Klink\Network;


/**
*  RestClient adapter to mask the underlying library used. Exposes shortcut methods for common operations
*/
class RestClient
{
	
	private $baseApiUrl = null;


	private INetworkTransport $transport = null;


	function __construct($baseApiUrl, array $options = null)
	{
		# code...

		$this->baseApiUrl = $baseApiUrl;

		$this->transport = new KlinkHttp();
	}


	function get(array $params = null){

	}

	function post(array $params = null){

	}

	function put(array $params = null){

	}

	function delete(array $params = null){

	}
}



?>