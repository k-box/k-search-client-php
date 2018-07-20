<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCRequest;
use JMS\Serializer\Annotation as JMS;

/**
 * ##SWG\Definition(
 *     definition="Data\AddRequest",
 *     required={"params"}
 * )
 */
class AddRequest extends RPCRequest
{
    /**
     * @var AddParams
     * ##AssertValid()
     * ##AssertNotNull()
     * @JMS\Type("KSearchClient\Model\Data\AddParams")
     * ##SWG\Property()
     */
    public $params;
}
