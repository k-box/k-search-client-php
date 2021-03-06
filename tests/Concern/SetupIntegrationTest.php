<?php
namespace Tests\Concern;

use KSearchClient\Client;
use KSearchClient\Http\Authentication;
use KSearchClient\Model\Search\SearchParams;

trait SetupIntegrationTest
{

    /**
     * @var KSearchClient\Client
     */
    protected $client = null;
    
    protected function setUp(): void
    {
        parent::setUp();

        $auth = new Authentication(getenv('KSEARCH_APP_SECRET'), getenv('KSEARCH_APP_URL'));
        
        $service_url = getenv('KSEARCH_URL');
        $api_version = $this->getApiVersion();

        if(empty($service_url)){
            $this->markTestSkipped(
                'The KSEARCH_URL is not configured.'
                );
        }
        
        $this->client = Client::build($service_url, $auth, $api_version);
    }

    protected function getApiVersion()
    {
        $api_version = getenv('KSEARCH_VERSION'); 
        return  $api_version ? $api_version : '3.7';
    }

    public function skipIfApiVersionNotEqualOrAbove($version)
    {
        $envVersion = $this->getApiVersion();

        if(version_compare($envVersion, $version, '<')){
            $this->markTestSkipped(
                "Test skipped as require api [$version]. Environment version [$envVersion]."
            );
        }
    }

    protected function clearIndexedDocuments()
    {
        $params = tap(new SearchParams(), function($searchParams) {
            $searchParams->search = '*'; // all indexed data
            $searchParams->filters = null;
            $searchParams->offset = 0;
            $searchParams->limit = 50;
        });

        $list = $this->client->search($params)->items;

        foreach ($list as $item) {
            $this->client->delete($item->uuid);
        }
    }
    
}
