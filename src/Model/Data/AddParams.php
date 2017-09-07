<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

class AddParams
{
    /**
     * The Data object to be added.
     *
     * @var Data
     * @Assert\NotBlank()
     * @Assert\Valid()
     * @JMS\Type("KSearchClient\Model\Data\Data")
     */
    public $data;

    /**
     * A plain text data with information that will be used for full-text searches to match the given Data.
     *
     * This should only be provided for data representing files which are not supported by the text-extraction system in
     * the KSearch component (such as compressed files, geo files or video files)
     *
     * @var string
     * @JMS\Type("string")
     */
    public $dataTextualContents;
}
