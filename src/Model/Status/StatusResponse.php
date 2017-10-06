<?php

namespace KSearchClient\Model\Status;

use KSearchClient\Model\RPCResponse;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * ##SWG\Definition(
 *     definition="Status\StatusResponse",
 *     required={"result"}
 * )
 */
class StatusResponse extends RPCResponse
{
    /**
     * The status data.
     *
     * @var Status
     *
     * @JMS\Type("KSearchClient\Model\Status\Status")
     * ##SWG\Property(
     *     readOnly=true,
     *     ref="#/definitions/Status\Status")
     * )
     */
    public $result;

    /**
     * @param string $responseId
     */
    public function __construct(Status $status, $responseId = null)
    {
        parent::__construct($responseId);
        $this->result = $status;
    }

    /**
     * @param int $statusCode
     * @param string $message
     * @param string $responseId
     * @return StatusResponse
     */
    public static function withStatusMessage($statusCode, $message, $responseId = null)
    {
        return new self(new Status($statusCode, $message), $responseId);
    }
}
