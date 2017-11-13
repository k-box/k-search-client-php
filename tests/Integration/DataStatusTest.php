<?php
namespace Tests\Integration;

use Tests\TestCase;
use KSearchClient\Client;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\DataStatus;
use KSearchClient\Http\Authentication;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Exception\InvalidDataException;
use Tests\Concern\SetupIntegrationTest;

class DataStatusTest extends TestCase
{
    use SetupIntegrationTest;

    public function testClientHandleErrorDataStatus()
    {
        $uuid = Uuid::uuid4()->toString();
        $failure_generator_server = getenv('FAILURE_GENERATOR_SERVER');

        if(empty($failure_generator_server)){
            $this->markTestSkipped(
                'Fake failure server not configured.'
                );
        }

        $to_add = $this->createDataModel($uuid, "http://$failure_generator_server");

        $added_data = $this->client->add($to_add);

        sleep(20); //just waiting some seconds to let the K-Search understand that there is a file to process

        $status = $this->client->getStatus($uuid);
        
        $this->assertInstanceOf(DataStatus::class, $status);
        $this->assertInternalType('string', $status->status);
        $this->assertEquals('error', $status->status);
        $this->assertNotEmpty($status->message);
    }

}