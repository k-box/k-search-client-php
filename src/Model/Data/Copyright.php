<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\Copyright",
 *     description="An object containing information on the copyright",
 *     required={"owner", "usage"}
 * )
 */
class Copyright
{
    /**
     * The copyright owner and information on how to contact for any inquiries.
     *
     * @var CopyrightOwner
     * ##AssertNotBlank()
     * ##AssertValid()
     * @JMS\Type("KSearchClient\Model\Data\CopyrightOwner")
     * ##SWG\Property()
     */
    public $owner;

    /**
     * The conditions of use of the copyrighted data.
     *
     * @var CopyrightUsage
     * ##AssertNotBlank()
     * ##AssertValid()
     * @JMS\Type("KSearchClient\Model\Data\CopyrightUsage")
     * ##SWG\Property()
     */
    public $usage;
}
