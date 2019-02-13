<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;

/**
 * Represent a K-Link instance
 */
class Klink
{
    /**
     * The K-Link identifier.
     *
     * @var string
     * @JMS\Type("string")
     */
    public $id;

    /**
     * The name of the K-Link.
     *
     * @var string
     * @JMS\Type("string")
     */
    public $name;
}
