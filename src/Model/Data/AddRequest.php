<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCRequest;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\AddRequest",
 *     required={"params"}
 * )
 */
class AddRequest extends RPCRequest
{
    /**
     * @var AddParams
     * @Assert\Valid()
     * @Assert\NotNull()
     * @JMS\Type("KSearchClient\Model\Data\AddParams")
     * ##SWG\Property()
     */
    public $params;
}
