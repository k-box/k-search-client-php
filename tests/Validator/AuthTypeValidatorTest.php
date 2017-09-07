<?php
namespace KSearchClient\Tests\Validator;

use Http\Message\Authentication\AutoBasicAuth;
use Http\Message\Authentication\BasicAuth;
use Http\Message\Authentication\Bearer;
use Http\Message\Authentication\Chain;
use Http\Message\Authentication\RequestConditional;
use Http\Message\Authentication\Wsse;
use KSearchClient\Validator\AuthTypeValidator;
use PHPUnit\Framework\TestCase;

class AuthTypeValidatorTest extends TestCase
{

    public function testItAcceptsBasicAuth()
    {
        $basicAuth = new BasicAuth('user', 'pass');
        $this->assertTrue(AuthTypeValidator::isSupported($basicAuth));
    }

    public function testItAcceptsBearercAuth()
    {
        $basicAuth = new Bearer('token');
        $this->assertTrue(AuthTypeValidator::isSupported($basicAuth));
    }

    public function testItDoesNotSupportOtherAuthTypes()
    {
        $this->assertFalse(AuthTypeValidator::isSupported(new Chain()));
        $this->assertFalse(AuthTypeValidator::isSupported(new AutoBasicAuth()));
    }
}