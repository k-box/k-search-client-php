<?php

namespace KSearchClient\Model\Search;

use KSearchClient\Model\RPCResponse;
use JMS\Serializer\Annotation as JMS;
// use Swagger\Annotations as SWG;

/**
 * ##SWG\Definition(
 *     definition="KSearchClient\Model\Search\SearchResponse",
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
     * @JMS\Type("KSearchClient\Model\Search\SearchResults")
     * @JMS\ReadOnly()
     * ##SWG\Property()
     */
    public $result;

    public function __construct($result, string $responseId = null)
    {
        parent::__construct($responseId);
        $this->result = $result;
    }
}
