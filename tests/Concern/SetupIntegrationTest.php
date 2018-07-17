<?php
namespace Tests\Concern;

use KSearchClient\Client;
use KSearchClient\Http\Authentication;

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
    
}
