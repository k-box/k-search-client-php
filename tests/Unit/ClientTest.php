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

class ClientTest extends TestCase
{
    public function testClientCanBeBuiltWithAuthentication()
    {
        $auth = new Authentication('token', 'http://localhost');

        $service_url = 'https://search.klink.asia/';

        $client = Client::build($service_url, $auth);

        $this->assertInstanceOf(Client::class, $client);
    }
    
    public function testClientCanBeBuiltWithNoAuthentication()
    {
        $service_url = 'https://search.klink.asia/';

        $client = Client::build($service_url);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testClientConstructRequestsWithAuthentication()
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
            '{"id": "hello", "result": {"status":"ok"}}'
        ));
        
        $client = new Client($service_url, $auth, $factory, $serializer, $httpClient, $messageFactory);

        $uuid = '00000000-0000-0000-0000-000000000001';

        $status = $client->getStatus($uuid);
        
        $sentRequests = $httpClient->getRequests();
                
        $this->assertEquals(['http://localhost'], $sentRequests[0]->getHeader('Origin'));
        $this->assertEquals(['Bearer token'], $sentRequests[0]->getHeader('Authorization'));
    }
    
    public function testClientSendAndAcceptJson()
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
            '{"id": "hello", "result": {"status":"ok"}}'
        ));
        
        $client = new Client($service_url, $auth, $factory, $serializer, $httpClient, $messageFactory);

        $uuid = '00000000-0000-0000-0000-000000000001';

        $status = $client->getStatus($uuid);
        
        $sentRequests = $httpClient->getRequests();
                
        $this->assertEquals(['application/json'], $sentRequests[0]->getHeader('Content-Type'));
        $this->assertEquals(['application/json'], $sentRequests[0]->getHeader('Accept'));
    }
}