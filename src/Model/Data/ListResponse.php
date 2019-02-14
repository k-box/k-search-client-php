<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCResponse;
use JMS\Serializer\Annotation as JMS;

class ListResponse extends RPCResponse
{
    /**
     * The response data.
     *
     * @var Klink[]
     *
     * @JMS\Type("array<KSearchClient\Model\Data\Klink>")
     */
    public $result;
}
