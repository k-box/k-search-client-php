<?php
namespace Tests\Integration;

use Tests\TestCase;
use KSearchClient\Model\Data\Data;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use Tests\Concern\SetupIntegrationTest;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Exception\InvalidDataException;

class WorkflowTest extends TestCase
{
    use SetupIntegrationTest;

    /*
     * This simulates a workflow that adds a Data, check 
     * if is processed, retrieves all the details and 
     * then deletes the previously added data
     */

    public function testWorkflowAddsData()
    {
        $uuid = Uuid::uuid4()->toString();

        $added_data = $this->client->add($this->createDataModel($uuid), 'textual content to use');

        $this->assertInstanceOf(Data::class, $added_data);

        return $uuid;
    }

    /**
     * @depends testWorkflowAddsData
     */
    public function testWorkflowRetrievesStatusForRecentlyAddedData($uuid)
    {
        $status = $this->client->getStatus($uuid);

        $this->assertNotEmpty($status);
        $this->assertInternalType('string', $status);
        $this->assertEquals('ok', $status);

        return $uuid;
    }

    /**
     * @depends testWorkflowRetrievesStatusForRecentlyAddedData
     */
    public function testWorkflowRetrievesAddedData($uuid)
    {
        $data = $this->client->get($uuid);

        $this->assertInstanceOf(Data::class, $data);

        return $uuid;
    }
    
    /**
     * @depends testWorkflowRetrievesAddedData
     */
    public function testWorkflowDeletesData($uuid)
    {
        $is_deleted = $this->client->delete($uuid);

        $this->assertInternalType('bool', $is_deleted);
        $this->assertTrue($is_deleted);
    }
}