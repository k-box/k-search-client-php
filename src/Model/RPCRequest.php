<?php

namespace KSearchClient\Model;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * The main class for RPC requests.
 */
class RPCRequest
{
    const REQUEST_ID_HEADER = 'KSearch-Request-Id';

    /**
     * A request identifier established by the client that MUST contain a string or a number.
     *
     * The value SHOULD normally not be Null and Numbers SHOULD NOT contain fractional parts.
     *
     * @var string
     *
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="request-3d254173"
     * )
     */
    public $id;
}