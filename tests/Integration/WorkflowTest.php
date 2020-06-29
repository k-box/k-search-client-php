<?php

namespace Tests\Integration;

use GuzzleHttp\Psr7\Request;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\DataStatus;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use Tests\Concern\SetupIntegrationTest;
use Tests\TestCase;

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
        /** @var DataStatus $status */
        $status = $this->client->getStatus($uuid);

        $this->assertInstanceOf(DataStatus::class, $status);
        $this->assertIsString($status->status);
        $this->assertEquals(DataStatus::STATUS_INDEX_OK, $status->status);
        $this->assertTrue($status->indexed());
        $this->assertEmpty($status->message);

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

        $this->assertIsBool($is_deleted);
        $this->assertTrue($is_deleted);
    }

}