<?php

namespace KSearchClient\Model\Search;

use KSearchClient\Model\RPCRequest;
use JMS\Serializer\Annotation as JMS;
// use Swagger\Annotations as SWG;
// use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="KSearchClient\Model\Search\SearchRequest",
 *     required={"params"}
 * )
 */
class SearchRequest extends RPCRequest
{
    /**
     * @var SearchParams
     * ##Assert\Valid()
     * ##Assert\NotNull()
     * @JMS\Type("KSearchClient\Model\Search\SearchParams")
     * ##SWG\Property()
     */
    public $params;
}
