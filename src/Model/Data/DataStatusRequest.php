<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCRequest;
use JMS\Serializer\Annotation as JMS;

class DataStatusRequest extends RPCRequest
{
    /**
     * @var UUIDParam
     * @JMS\Type("KSearchClient\Model\Data\UUIDParam")
     */
    public $params;
}
