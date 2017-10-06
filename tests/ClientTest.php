<?php
namespace Tests;

use KSearchClient\Client;
use KSearchClient\Http\Authentication;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class ClientTest extends TestCase
{
    public function testClientCanBeBuilt()
    {
        $auth = new Authentication('token', 'http://localhost');

        $service_url = 'https://search.klink.asia/';

        $client = Client::build($service_url, $auth);

        $this->assertInstanceOf(Client::class, $client);
    }
}