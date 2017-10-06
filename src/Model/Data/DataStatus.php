<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;

/**
 * ##SWG\Definition(
 *     definition="Data\DataStatus",
 *     required={"code", "status"}
 * )
 */
class DataStatus
{
    /**
     * The status.
     *
     * @var string
     * @JMS\Type("string")
     * ##JMS\ReadOnly()
     * ##SWG\Property(
     *     example="queued",
     * )
     */
    public $status;

    /**
     * @param string $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }
}
