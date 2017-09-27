<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCRequest;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\DeleteRequest",
 *     required={"params"}
 * )
 */
class DeleteRequest extends RPCRequest
{
    /**
     * @var UUIDParam
     * ##AssertValid()
     * ##AssertNotNull()
     * @JMS\Type("KSearchClient\Model\Data\UUIDParam")
     * ##SWG\Property()
     */
    public $params;
}
