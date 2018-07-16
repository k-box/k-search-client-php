<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCResponse;
use JMS\Serializer\Annotation as JMS;

/**
 * ##SWG\Definition(
 *     definition="Data\AddResponse",
 *     required={"result"}
 * )
 */
class AddResponse extends RPCResponse
{
    /**
     * The response data.
     *
     * @var Data
     *
     * @JMS\Type("KSearchClient\Model\Data\Data")
     * ##JMS\ReadOnly()
     * ##SWG\Property(
     *     ref="#/definitions/Data\Data")
     * )
     */
    public $result;

    public function __construct(Data $data, string $responseId = null)
    {
        parent::__construct($responseId);
        $this->result = $data;
    }
}
