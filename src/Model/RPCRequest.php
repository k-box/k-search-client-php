<?php

namespace KSearchClient\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * The main class for RPC requests.
 */
class RPCRequest
{
    const REQUEST_ID_HEADER = 'KSearch-Request-Id';

    /**
     * A request identifier established by the client.
     *
     * @var string
     * @JMS\Type("string")
     */
    public $id;
}
