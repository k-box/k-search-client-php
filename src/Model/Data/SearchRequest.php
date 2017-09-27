<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCRequest;
use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\SearchRequest",
 *     required={"params"}
 * )
 */
class SearchRequest extends RPCRequest
{
    /**
     * @var SearchParams
     * ##AssertValid()
     * ##AssertNotNull()
     * @JMS\Type("KSearchClient\Model\Data\SearchParams")
     * ##SWG\Property()
     */
    public $params;
}
