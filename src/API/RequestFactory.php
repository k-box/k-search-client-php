<?php
namespace KSearchClient\API;

use KSearchClient\Model\Data\AddParams;
use KSearchClient\Model\Data\AddRequest;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\DataStatusRequest;
use KSearchClient\Model\Data\DeleteRequest;
use KSearchClient\Model\Data\GetRequest;
use KSearchClient\Model\Data\SearchParams;
use KSearchClient\Model\Data\SearchRequest;
use KSearchClient\Model\Data\UUIDParam;
use KSearchClient\Model\Status\StatusResponse;

class RequestFactory
{
    /**
     * @var IDGenerator
     */
    private $idGenerator;


    /**
     * APIRequestFactory constructor.
     */
    public function __construct(IDGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    public function buildDataAddRequest(Data $data, $dataTextualContents): AddRequest
    {
        $addRequest = new AddRequest();
        $addRequest->params = new AddParams();
        $addRequest->params->data = $data;
        $addRequest->params->dataTextualContents = $dataTextualContents;
        $addRequest->id = $this->idGenerator->getNewId();

        return $addRequest;
    }

    public function buildGetRequest($uuid)
    {
        $getRequest = new GetRequest();
        $getRequest->id = $this->idGenerator->getNewId();
        $getRequest->params = new UUIDParam();
        $getRequest->params->uuid = $uuid;

        return $getRequest;
    }

    public function buildDeleteRequest(string $uuid): DeleteRequest
    {
        $deleteRequest = new DeleteRequest();
        $deleteRequest->id = $this->idGenerator->getNewId();
        $deleteRequest->params = new UUIDParam();
        $deleteRequest->params->uuid = $uuid;

        return $deleteRequest;
    }

    public function buildSearchRequest(string $uuid): SearchRequest
    {
        $searchRequest = new SearchRequest();
        $searchRequest->id = $this->idGenerator->getNewId();
        $searchRequest->params = new SearchParams;
        $searchRequest->params;

        return $searchRequest;
    }

    public function buildStatusRequest(string $uuid): DataStatusRequest
    {
        $statusRequest= new DataStatusRequest();
        $statusRequest->id = $this->idGenerator->getNewId();
        $statusRequest->params = new UUIDParam;
        $statusRequest->params->uuid = $uuid;

        return $statusRequest;
    }

    public static function buildDefault()
    {
        return new self(new IDGenerator());
    }
}