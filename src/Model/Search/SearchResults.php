<?php

namespace KSearchClient\Model\Search;

use KSearchClient\Model\Data\Data;
use JMS\Serializer\Annotation as JMS;
// use Swagger\Annotations as SWG;
// use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\Search\SearchResults",
 *     required={"query","query_time","total_matches"}
 * )
 */
class SearchResults
{
    /**
     * The complete SearchQuery object from the request.
     *
     * @var SearchParams
     * @JMS\Type("KSearchClient\Model\Search\SearchParams")
     * ##JMS\ReadOnly()
     * ##SWG\Property()
     */
    public $query;

    /**
     * The time needed to run the search query.
     *
     * @var int
     * @JMS\Type("integer")
     * @JMS\SerializedName("query_time")
     * ##JMS\ReadOnly()
     * ##SWG\Property(
     *     property="query_time",
     *     example="104",
     * )
     */
    public $queryTime;

    /**
     * The total amount of found items.
     *
     * @var int
     * @JMS\Type("integer")
     * @JMS\SerializedName("total_matches")
     * ##JMS\ReadOnly()
     * ##SWG\Property(
     *     property="total_matches",
     *     example="1",
     * )
     */
    public $totalMatches;

    /**
     * Array of aggregations.
     *
     * @var array
     * ##Assert\Valid()
     * @JMS\Type("array<string,array<KSearchClient\Model\Search\AggregationResult>>")
     * ##SWG\Property(
     *     ##SWG\Items(
     *        type="array",
     *        ##SWG\Items(ref="#/definitions/Data\Search\AggregationResult")
     *     ),
     * )
     */
    public $aggregations;

    /**
     * Array of results.
     *
     * @var Data[]
     * @JMS\Type("array<KSearchClient\Model\Data\Data>")
     * ##SWG\Property()
     */
    public $items;

    public function __construct(SearchParams $query)
    {
        $this->query = $query;
    }
}
