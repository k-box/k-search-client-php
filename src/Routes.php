<?php
namespace KSearchClient;

class Routes
{
    const DATA_ADD_ENDPOINT = 'data.add';

    /** @var string */
    private $baseUrl;

    public function __construct(string $baseUrl)
    {
        $baseUrl = trim($baseUrl);
        $this->baseUrl = trim($baseUrl, '/');
    }

    /**
     * @return string
     */
    public function getDataAdd(): string
    {
        return $this->buildURL(self::DATA_ADD_ENDPOINT);
    }

    /**
     * @return string
     */
    protected function buildURL($endpoint): string
    {
        return sprintf('%s/%s', $this->baseUrl, $endpoint);
    }
}