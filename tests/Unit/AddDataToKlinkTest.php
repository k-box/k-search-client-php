<?php
namespace Tests\Unit;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use KSearchClient\Client;
use KSearchClient\Http\RequestFactory;
use KSearchClient\Http\Authentication;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use JMS\Serializer\SerializerBuilder;
use Http\Mock\Client as HttpMockClient;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Http\Discovery\MessageFactoryDiscovery;

class AddDataToKlinkTest extends TestCase
{ 

    public function testClientCanAddDataWithToKlink()
    {
        AnnotationRegistry::registerLoader('class_exists');

        $auth = new Authentication('token', 'http://localhost');

        $service_url = 'https://search.klink.asia/';

        $factory = new RequestFactory;
        $serializer = SerializerBuilder::create()->build();
        $httpClient = new HttpMockClient();
        $messageFactory = MessageFactoryDiscovery::find();
        $httpClient->addResponse(new Response(200, 
            ['Content-Type' => 'application/json'],
            '{"id": "hello", "result": [{"id":"cc1bbc0b-20e8-4e1f-b894-fb067e81c5dd", "name": "Sustainable Land Management"}]}'
        ));
        
        $client = new Client($service_url, $auth, $factory, $serializer, $httpClient, $messageFactory);

        $uuid = Uuid::uuid4()->toString();

        $to_add = $this->createDataModel($uuid);

        
        $added_data = $client->addToKlink($to_add, ['1'], 'textual content to use');
        
        $requests = $httpClient->getRequests();

        $this->assertCount(1, $requests);

        $body = json_decode((string)$requests[0]->getBody());
        
        $this->assertEquals(['1'], $body->params->klinks);

    }

}