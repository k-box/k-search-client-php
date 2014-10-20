<?php


/**
* 
*/
final class KlinkConfiguration
{
	


	/**
	 * institution_id
	 * @var string
	 */

	private $institution_id;

	/**
	 * getInstitution_id
	 * @return string
	 */
	public function getInstitutionId() {
		return $this->institution_id;
	}

	/**
	 * cores
	 * @var KlinkAuthentication
	 */

	private $cores;

	/**
	 * Get the Klink Core to use from this adapter, each with the authentication informations.
	 * @return KlinkAuthentication[]
	 */
	public function getCores() {
		return $this->cores;
	}





	function __construct()
	{
		# code...

		// array $core_api_url = array("https://localhost/kcore/"),
	}






}