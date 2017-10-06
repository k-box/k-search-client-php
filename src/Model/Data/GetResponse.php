<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCResponse;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * ##SWG\Definition(
 *     definition="Data\GetResponse",
 *     required={"result"}
 * )
 */
class GetResponse extends RPCResponse
{
    /**
     * The response data.
     *
     * @var Data
     *
     * @JMS\Type("KSearchClient\Model\Data\Data")
     * ##JMS\ReadOnly()
     * ##SWG\Property()
     */
    public $result;

    /**
     * @param string $responseId
     */
    public function __construct(Data $data, $responseId = null)
    {
        $this->result = $data;
        $this->id = $responseId;
    }
}
