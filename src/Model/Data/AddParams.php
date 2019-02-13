<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;

/**
 * ##SWG\Definition(
 *     definition="Data\AddParams",
 *     required={"data"}
 * )
 */
class AddParams
{
    /**
     * The Data object to be added.
     *
     * @var Data
     * ##AssertNotBlank()
     * ##AssertValid()
     * @JMS\Type("KSearchClient\Model\Data\Data")
     * ##SWG\Property()
     */
    public $data;
    
    /**
     * The K-Links to which the data needs to be published.
     *
     * Use the K-Link identifiers
     *
     * @var array
     * @JMS\Type("array<string>")
     * @JMS\Since("3.7")
     */
    public $klinks = [];

    /**
     * A plain text data with information that will be used for full-text searches to match the given Data.
     *
     * This should only be provided for data representing files which are not supported by the text-extraction system in
     * the KSearch component (such as compressed files, geo files or video files)
     *
     * @var string
     * @JMS\Type("string")
     * ##SWG\Property()
     */
    public $dataTextualContents;
}
