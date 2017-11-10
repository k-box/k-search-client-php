<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCResponse;
use JMS\Serializer\Annotation as JMS;

class DataStatusResponse extends RPCResponse
{
    /**
     * The data Status.
     *
     * @var DataStatus
     *
     * @JMS\Type("KSearchClient\Model\Data\DataStatus")
     */
    public $result;
}
