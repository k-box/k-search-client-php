<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;

/**
 * ##SWG\Definition(
 *     definition="Data\Data",
 *     required={"uuid", "hash", "type", "url", "author", "copyright", "uploader", "properties"}
 * )
 */
class Data
{
    const DATA_TYPE_DOCUMENT = 'document';
    const DATA_TYPE_VIDEO = 'video';

    /**
     * The Universally unique identifier of this data.
     *
     * @var string
     * ##AssertNotBlank()
     * ##AssertUuid()
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="cc1bbc0b-20e8-4e1f-b894-fb067e81c5dd",
     * )
     */
    public $uuid;

    /**
     * The URI where the source data is stored and retrievable.
     *
     * @var string
     * ##AssertNotBlank()
     * ##AssertUrl()
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="http://publicliterature.org/pdf/advsh12.pdf",
     * )
     */
    public $url;

    /**
     * The SHA-2 hash of the Document contents (SHA-512, thus 128 Chars).
     *
     * @var string
     * ##AssertNotBlank()
     * ##AssertLength(min="128", max="128")
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="d6f644b19812e97b5d871658d6d3400ecd4787faeb9b8990c1e7608288664be77257104a58d033bcf1a0e0945ff06468ebe53e2dff36e248424c7273117dac09",
     * )
     */
    public $hash;

    
    /**
     * The K-Links to which this data is published.
     *
     * @var Klink[]
     * @JMS\ReadOnly()
     * @JMS\Type("array<KSearchClient\Model\Data\Klink>")
     * @JMS\Since("3.7")
     */
    public $klinks = [];

    /**
     * The general type of the provided data.
     *
     * @var string
     * ##AssertNotNull()
     * @JMS\Type("string")
     * ##AssertChoice(
     *     strict=true,
     *     choices={"document", "video"}
     * )
     * ##SWG\Property(
     *     enum={"document", "video"}
     * )
     */
    public $type;

    /**
     * The Geo location of the data, as an escaped GeoJson string.
     * 
     * The coordinates must be in the WGS84 coordinate system.
     * The order of the coordinates must be longitude, latitude
     * 
     * @var string|GeographicGeometry
     * @JMS\Type("string")
     * @JMS\Since("3.5")
     */
    public $geo_location;

    /**
     * The properties of the data.
     *
     * @var Properties
     * ##AssertNotBlank()
     * ##AssertValid()
     * @JMS\Type("KSearchClient\Model\Data\Properties")
     * ##SWG\Property()
     */
    public $properties;

    /**
     * List of authors (multiple).
     *
     * @var Author[]
     * ##AssertNotBlank()
     * ##AssertValid()
     * @JMS\Type("array<KSearchClient\Model\Data\Author>")
     * ##SWG\Property()
     */
    public $authors;

    /**
     * Information on the copyright.
     *
     * @var Copyright
     * ##AssertNotBlank()
     * ##AssertValid()
     * @JMS\Type("KSearchClient\Model\Data\Copyright")
     * ##SWG\Property()
     */
    public $copyright;

    /**
     * The originating source where the data has been uploaded or created.
     *
     * @var Uploader
     * ##AssertNotBlank()
     * ##AssertValid()
     * @JMS\Type("KSearchClient\Model\Data\Uploader")
     * ##SWG\Property()
     */
    public $uploader;
}
