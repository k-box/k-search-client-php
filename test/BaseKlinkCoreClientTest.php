<?php

abstract class BaseKlinkCoreClientTest extends PHPUnit_Framework_TestCase
{
    /** @var KlinkCoreClient */
    private $coreClient;

    /** @var string */
    protected $coreUser;

    /** @var string */
    protected $corePass;

    /** @var string */
    protected $corePrivateUrl;

    /** @var string */
    protected $corePublicUrl;

    /** @var string */
    protected $institutionId;

    /** @var string */
    protected $adapterId;

    /** @var  \Psr\Log\LoggerInterface */
    private $logger;

    public function setUp()
    {
        parent::setUp();
        $this->initSettings();
    }

    public function initSettings() {
        if ($this->logger) {
            return;
        }
        $this->logger = new \LoggerMock();
        // Main configurations
        $this->coreUser = $_ENV['KLINK_USER'];
        $this->corePass = $_ENV['KLINK_PASS'];
        $this->institutionId = $_ENV['KLINK_INSTITUTION_ID'];
        $this->adapterId = $_ENV['KLINK_ADAPTER_ID'];
        $this->corePublicUrl = $_ENV['KLINK_CORE_PUBLIC_URL'];
        $this->corePrivateUrl = $_ENV['KLINK_CORE_PRIVATE_URL'];
    }


    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger() {
        return $this->logger;
    }

    /**
     * @return KlinkCoreClient
     */
    public function getCoreClient() {
        if ($this->coreClient) {
            return $this->coreClient;
        }
        $this->initSettings();

        $auth_public = new KlinkAuthentication(
            $this->corePublicUrl,
            $this->coreUser,
            $this->corePass,
            \KlinkVisibilityType::KLINK_PUBLIC
        );
        $auth_private = new KlinkAuthentication(
            $this->corePrivateUrl,
            $this->coreUser,
            $this->corePass,
            \KlinkVisibilityType::KLINK_PRIVATE
        );
        $config = new KlinkConfiguration($this->institutionId, $this->adapterId, array($auth_public, $auth_private));

        if (in_array('--debug', $_SERVER['argv'])) {
            $config->enableDebug();
        }

        $this->coreClient = new KlinkCoreClient($config);

        return $this->coreClient;
    }
}
