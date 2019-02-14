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

class KlinkListTest extends TestCase
{
    use SetupIntegrationTest;

    public function testEmptyListIsReturned()
    {
        $this->skipIfApiVersionNotEqualOrAbove('3.7');

        $data = $this->client->klinks();

        $this->assertEmpty($data);
    }
}