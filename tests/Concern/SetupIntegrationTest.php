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
    
    protected function setUp()
    {
        parent::setUp();

        $auth = new Authentication(getenv('KSEARCH_APP_SECRET'), getenv('KSEARCH_APP_URL'));
        
        $service_url = getenv('KSEARCH_URL');
        $api_version = getenv('KSEARCH_VERSION');

        if(empty($service_url)){
            $this->markTestSkipped(
                'The KSEARCH_URL is not configured.'
                );
        }
        
        $this->client = Client::build($service_url, $auth, $api_version ? $api_version : '3.4');
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
