<?php
namespace Tests\Unit;

use Tests\TestCase;
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

class KlinkListTest extends TestCase
{ 
    public function testKlinkListIsReturned()
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

        $klinks = $client->klinks();
                
        $this->assertNotEmpty($klinks);
        $this->assertEquals("cc1bbc0b-20e8-4e1f-b894-fb067e81c5dd", $klinks[0]->id);
        $this->assertEquals("Sustainable Land Management", $klinks[0]->name);
    }
}