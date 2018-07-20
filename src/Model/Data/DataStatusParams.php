<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;
// use Swagger\Annotations as SWG;
// use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\DataStatusParams",
 *     required={"uuid"}
 * )
 */
class DataStatusParams extends UUIDParam
{
    /**
     * The status type, used to get the status from different indexing or processing stages.
     * Use `data` for the indexed Data status, `processing` for the processing queue Data status.
     *
     * @var string
     * ##Assert\Choice(callback="getTypes")
     * @JMS\Type("string")
     * ##SWG\Property(
     *     enum={"data","processing"},
     *     default="data",
     *     x={"since-version":"3.4"},
     * )
     */
    public $type = DataStatus::TYPE_DATA;

    /**
     * 
     * @return array
     */
    public static function getTypes()
    {
        return [
            DataStatus::TYPE_DATA,
            DataStatus::TYPE_PROCESSING,
        ];
    }
}
