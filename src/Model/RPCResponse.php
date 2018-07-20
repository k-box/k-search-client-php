<?php

namespace KSearchClient\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * The base class for RPC responses.
 */
class RPCResponse
{
    /**
     * The request ID this response is referring to.
     *
     * @see RPCRequest::$id
     *
     * @var string
     * @JMS\Type("string")
     * ##JMS\ReadOnly()
     */
    public $id;

    /**
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }
}
