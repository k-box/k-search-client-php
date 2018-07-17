<?php

namespace KSearchClient\Model\Search;

use JMS\Serializer\Annotation as JMS;
// use Swagger\Annotations as SWG;
// use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\Search\SortParam",
 *     required={"field"}
 * )
 */
class SortParam
{
    const ORDER_DESC = 'desc';
    const ORDER_ASC = 'asc';

    /**
     * Field to apply the sorting on, check the documentation for the list of available fields.
     *
     * @var string
     * ##Assert\NotBlank()
     * ##Assert\Choice(callback="getSortableFields")
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="_score",
     * )
     */
    public $field;

    /**
     * Field sorting order.
     *
     * @var string
     * ##Assert\NotBlank()
     * ##Assert\Choice(callback="getSortOrders")
     * @JMS\Type("string")
     * ##SWG\Property(
     *     enum={"asc","desc"},
     *     default="desc",
     * )
     */
    public $order = self::ORDER_DESC;

    /**
     * @return array
     */
    public static function getSortOrders()
    {
        return [
            self::ORDER_ASC,
            self::ORDER_DESC,
        ];
    }

    /**
     * @return array
     */
    public static function getSortableFields()
    {
        return [
            // Adding pseudo-field "_score"
            '_score',

            // Add regular fields
            'uuid',
            'type',
            'copyright.owner.name',
            'copyright.owner.email',
            'copyright.usage.short',
            'properties.created_at',
            'properties.language',
            'properties.mime_type',
            'properties.size',
            'properties.title',
            'properties.updated_at',
            'uploader.name',
            'uploader.organization',
            'uploader.app_url',
        ];
    }
}
