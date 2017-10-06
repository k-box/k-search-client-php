<?php

namespace KSearchClient\Model\Error;

use KSearchClient\Model\RPCResponse;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * ##SWG\Definition(
 *     definition="Error\ErrorResponse",
 *     required={"error"},
 * )
 */
class ErrorResponse extends RPCResponse
{
    /**
     * The error data.
     *
     * @var Error
     *
     * @JMS\Type("KSearchClient\Model\Error\Error")
     * ##SWG\Property(
     *     ref="#/definitions/Error\Error")
     * )
     */
    public $error;

    /**
     * @param string $responseId
     */
    public function __construct(Error $error, $responseId = null)
    {
        $this->error = $error;
        $this->id = $responseId;
    }

    /**
     * @param int $errorCode
     * @param string $errorMessage
     * @param string $responseId
     * @return ErrorResponse
     */
    public static function withErrorMessage($errorCode, $errorMessage, $responseId = null)
    {
        return new self(new Error($errorCode, $errorMessage), $responseId);
    }
}
