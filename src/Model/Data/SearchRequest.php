<?php

namespace KSearchClient\Model\Data;

use KSearchClient\Model\RPCRequest;
use Swagger\Annotations as SWG;

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
     * @Assert\Valid()
     * @Assert\NotNull()
     * @JMS\Type("KSearchClient\Model\Data\SearchParams")
     * ##SWG\Property()
     */
    public $params;
}
