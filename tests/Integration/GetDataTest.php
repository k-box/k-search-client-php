<?php
namespace Tests\Integration;

use Tests\TestCase;
use KSearchClient\Client;
use KSearchClient\Model\Data\Data;
use KSearchClient\Http\Authentication;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Exception\InvalidDataException;
use Tests\Concern\SetupIntegrationTest;

class GetDataTest extends TestCase
{
    use SetupIntegrationTest;

    public function testClientThrowsErrorIfNonExistentDataIsAsked()
    {
        $uuid = '00000000-0000-0000-0000-000000000001';

        $this->expectException(InvalidDataException::class);

        $data = $this->client->get($uuid);
    }
}