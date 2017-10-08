<?php
namespace Tests\Unit;

use Tests\TestCase;
use GuzzleHttp\Psr7\Request;
use KSearchClient\Http\Authentication;
use Psr\Http\Message\RequestInterface;
use KSearchClient\Http\NullAuthentication;

class AuthenticationTest extends TestCase
{
    public function testAuthenticationAppendHeaders()
    {
        $auth = new Authentication('token', 'http://localhost');

        $request = new Request('GET', 'http://ksearch.local');

        $request_with_authentication = $auth->authenticate($request);

        $this->assertInstanceOf(RequestInterface::class, $request_with_authentication);
        $this->assertEquals(['http://localhost'], $request_with_authentication->getHeader('Origin'));
        $this->assertEquals(['Bearer token'], $request_with_authentication->getHeader('Authorization'));
    }
    
    public function testNullAuthenticationDontModifyTheRequest()
    {
        $auth = new NullAuthentication();

        $request = new Request('GET', 'http://ksearch.local');

        $request_without_authentication = $auth->authenticate($request);

        $this->assertInstanceOf(RequestInterface::class, $request_without_authentication);
        $this->assertEquals(['Host' => ['ksearch.local']], $request_without_authentication->getHeaders());
        $this->assertEquals($request, $request_without_authentication);
    }
}