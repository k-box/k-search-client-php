<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCResponse;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * ##SWG\Definition(
 *     definition="Data\SearchResponse",
 *     required={"result"}
 * )
 */
class SearchResponse extends RPCResponse
{
    /**
     * The response data.
     *
     * @var Data
     *
     * @JMS\Type("KSearchClient\Model\Data\Data")
     * ##SWG\Property()
     */
    public $result;

    public function __construct($data, string $responseId = null)
    {
        // @todo include the correct response fields here
        $this->result = $data;
        $this->id = $responseId;
    }
}
