<?php


/**
 * The class that contains the configuration parameters for a KlinkCoreClient instance.
 * 
 * 
 * 
 * @package Klink
 */
final class KlinkConfiguration
{

	/**
	 * The institution identifier in the KLink network. This is chosen at configuration time
	 * @var string
	 */

	private $institution_id;

	/**
	 * The institution identifier in the KLink network
	 * @return string
	 */
	public function getInstitutionId() {
		return $this->institution_id;
	}

	/**
	 * The Klink Core connection details and authentication
	 * @var KlinkAuthentication
	 */

	private $cores;

	/**
	 * Get the Klink Cores to use from this adapter, each with the authentication informations.
	 * @return KlinkAuthentication[]
	 */
	public function getCores() {
		return $this->cores;
	}




	/**
	 * Creates a KlinkConfiguration instance to be used for configuring the KlinkCoreClient
	 * 
	 * @param string $institutionId 
	 * @param KlinkAuthentication[] $cores 
	 * @return KlinkConfiguration
	 */
	function __construct( $institutionId, array $cores )
	{

		KlinkHelpers::is_array_of_type($cores, 'KlinkAuthentication', 'cores');

		KlinkHelpers::is_string_and_not_empty( $institutionId, 'institution id');


		$this->institution_id = $institutionId;

		$this->cores = $cores;

	}

}