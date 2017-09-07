<?php
namespace KSearchClient\API;

use KSearchClient\Model\Data\AddParams;
use KSearchClient\Model\Data\AddRequest;
use KSearchClient\Model\Data\Data;

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

    public static function buildDefault()
    {
        return new self(new IDGenerator());
    }
}