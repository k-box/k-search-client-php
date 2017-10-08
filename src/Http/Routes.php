<?php
namespace KSearchClient\Http;

/**
 * @internal
 */
final class Routes
{
    const DATA_ADD_ENDPOINT = 'data.add';
    const DATA_DELETE_ENDPOINT = 'data.delete';
    const DATA_GET_ENDPOINT = 'data.get';
    const DATA_STATUS_ENDPOINT = 'data.status';
    const SEARCH_QUERY_ENDPOINT = 'data.search';

    /** @var string */
    private $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $baseUrl = trim($baseUrl);
        $this->baseUrl = trim($baseUrl, '/');
    }

    /**
     * @return string
     */
    public function getDataAdd()
    {
        return $this->buildURL(self::DATA_ADD_ENDPOINT);
    }

    /**
     * @return string
     */
    public function getDataDelete()
    {
        return $this->buildURL(self::DATA_DELETE_ENDPOINT);
    }

    /**
     * @return string
     */
    public function getDataGet()
    {
        return $this->buildURL(self::DATA_GET_ENDPOINT);
    }

    /**
     * @return string
     */
    public function getDataStatus()
    {
        return $this->buildURL(self::DATA_STATUS_ENDPOINT);
    }

    /**
     * @return string
     */
    public function getSearchQuery()
    {
        return $this->buildURL(self::SEARCH_QUERY_ENDPOINT);
    }

    /**
     * @return string
     */
    private function buildURL($endpoint)
    {
        return sprintf('%s/api/0.0/%s', $this->baseUrl, $endpoint);
    }
}