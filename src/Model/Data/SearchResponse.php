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
     * @var SearchResults
     *
     * @JMS\Type("KSearchClient\Model\Data\SearchResults")
     * ##JMS\ReadOnly()
     * ##SWG\Property()
     */
    public $result;

    /**
     * @param string $responseId
     */
    public function __construct($result, $responseId = null)
    {
        $this->result = $result;
        $this->id = $responseId;
    }
}
