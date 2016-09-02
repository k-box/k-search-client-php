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
     *
     * @var string
     */
    private $institution_id;

    /**
     * The adapter identifier
     *
     * @var string
     */
    private $adapter_id;

    /**
     * The institution identifier in the KLink network
     *
     * @return string
     */
    public function getInstitutionId()
    {
        return $this->institution_id;
    }

    /**
     * The Adapter identifier
     *
     * @return string
     */
    public function getAdapterId()
    {
        return $this->adapter_id;
    }

    /**
     * The Klink Core connection details and authentication
     *
     * @var KLinkAuthentication
     */
    private $cores;

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * Get the Klink Cores to use from this adapter, each with the authentication information.
     *
     * @return KlinkAuthentication[]
     */
    public function getCores()
    {
        return $this->cores;
    }

    public function enableDebug()
    {
        $this->debug = true;
    }

    public function disableDebug()
    {
        $this->debug = false;
    }

    public function isDebugEnabled()
    {
        return $this->debug;
    }


    /**
     * Creates a KLinkConfiguration instance to be used for configuring the KLinkCoreClient
     *
     * @param KLinkAuthentication[] $cores         the cores to be used from this institution
     * @param string                $adapterId     the identifier of the adapter
     * @param string                $institutionId the identifier of the institution
     *
     * @return KlinkConfiguration the KLinkConfiguration object to be used for configuring the KLinkCoreClient
     */
    function __construct($institutionId, $adapterId, array $cores)
    {
        KlinkHelpers::is_array_of_type($cores, 'KlinkAuthentication', 'cores');
        KlinkHelpers::is_valid_id($institutionId, 'institution id');
        KlinkHelpers::is_valid_id($adapterId, 'adapter id');

        $this->institution_id = $institutionId;
        $this->adapter_id = $adapterId;
        $this->cores = $cores;
    }
}
