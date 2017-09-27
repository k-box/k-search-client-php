<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ##SWG\Definition(
 *     definition="Data\CopyrightUsage",
 *     description="The conditions of use of the copyrighted data",
 *     required={"name", "short"},
 * )
 */
class CopyrightUsage
{
    /**
     * The associated usage permissions, expressed with the SPDX identifier (https://spdx.org/licenses/) and C for full copyright and PD for public domain.
     *
     * @var string
     * ##AssertNotBlank()
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="MPL-2.0",
     * )
     */
    public $short;

    /**
     * The associated usage permissions to the piece of data.
     * Examples: All right reserved, GNU General Public License, Public Domain.
     *
     * @var string
     * ##AssertNotBlank()
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="Mozilla Public License 2.0",
     * )
     */
    public $name;

    /**
     * URL of the full license text (if applicable).
     *
     * @var string
     * ##AssertUrl()
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="https://spdx.org/licenses/MPL-2.0.html",
     * )
     */
    public $reference;
}
