<?php
namespace Tests;

use KSearchClient\Http\Authentication;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class AuthenticationTest extends TestCase
{
    public function testHeadersAreAppendedToTheRequest()
    {
        $auth = new Authentication('token', 'http://localhost');

        $request = new Request('GET', 'http://ksearch.local');

        $request_with_authentication = $auth->authenticate($request);

        $this->assertInstanceOf(RequestInterface::class, $request_with_authentication);
        $this->assertEquals(['http://localhost'], $request_with_authentication->getHeader('Origin'));
        $this->assertEquals(['Bearer token'], $request_with_authentication->getHeader('Authorization'));
    }
}