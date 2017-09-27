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

    public function __construct(Error $error, string $responseId = null)
    {
        $this->error = $error;
        $this->id = $responseId;
    }

    public static function withErrorMessage(int $errorCode, string $errorMessage, string $responseId = null): ErrorResponse
    {
        return new self(new Error($errorCode, $errorMessage), $responseId);
    }
}
