<?php
namespace KSearchClient\Http;

use KSearchClient\Model\Data\AddParams;
use KSearchClient\Model\Data\AddRequest;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\DataStatusRequest;
use KSearchClient\Model\Data\DeleteRequest;
use KSearchClient\Model\Data\ListRequest;
use KSearchClient\Model\Data\GetRequest;
use KSearchClient\Model\Search\SearchParams;
use KSearchClient\Model\Search\SearchRequest;
use KSearchClient\Model\Data\UUIDParam;
use KSearchClient\Model\Data\DataStatus;
use KSearchClient\Model\Data\DataStatusParams;
use KSearchClient\Model\Status\StatusResponse;

class RequestFactory
{
    /**
     * @param Data $data
     * @param array $klinks
     * @param string $dataTextualContents
     * @return \KSearchClient\Model\Data\AddRequest
     */
    public function buildDataAddRequest(Data $data, array $klinks = [], $dataTextualContents = '')
    {
        $addRequest = new AddRequest();
        $addRequest->id = time();
        $addRequest->params = new AddParams();
        $addRequest->params->data = $data;
        $addRequest->params->klinks = $klinks;
        $addRequest->params->dataTextualContents = $dataTextualContents;

        return $addRequest;
    }

    public function buildGetRequest($uuid)
    {
        $getRequest = new GetRequest();
        $getRequest->id = time();
        $getRequest->params = new UUIDParam();
        $getRequest->params->uuid = $uuid;

        return $getRequest;
    }

    public function buildListRequest()
    {
        $listRequest = new ListRequest();
        $listRequest->id = time();

        return $listRequest;
    }

    /**
     * @param string $uuid
     * @return \KSearchClient\Model\Data\DeleteRequest
     */
    public function buildDeleteRequest($uuid)
    {
        $deleteRequest = new DeleteRequest();
        $deleteRequest->id = time();
        $deleteRequest->params = new UUIDParam();
        $deleteRequest->params->uuid = $uuid;

        return $deleteRequest;
    }

    /**
     * @return \KSearchClient\Model\Search\SearchRequest
     */
    public function buildSearchRequest(SearchParams $searchParams)
    {
        $searchRequest = new SearchRequest();
        $searchRequest->id = time();
        $searchRequest->params = $searchParams;

        return $searchRequest;
    }

    /**
     * @param string $uuid
     * @return \KSearchClient\Model\Data\DataStatusRequest
     */
    public function buildStatusRequest($uuid, $type = DataStatus::TYPE_DATA)
    {
        $statusRequest = new DataStatusRequest();
        $statusRequest->id = time();
        $statusRequest->params = new DataStatusParams;
        $statusRequest->params->uuid = $uuid;
        $statusRequest->params->type = $type;

        return $statusRequest;
    }
}