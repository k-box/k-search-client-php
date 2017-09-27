<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\AggregationResult",
 *     required={"value", "count"}
 * )
 */
class AggregationResult
{
    /**
     * The aggregation value.
     *
     * @var string
     * ##AssertNotBlank()
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="es"
     * )
     */
    public $value;

    /**
     * How many items in aggregation.
     *
     * @var int
     * ##AssertNotBlank()
     * @JMS\Type("integer")
     * ##SWG\Property(
     *     example="102"
     * )
     */
    public $count;
}
