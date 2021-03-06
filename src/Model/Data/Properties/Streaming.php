<?php

namespace KSearchClient\Model\Data\Properties;

use JMS\Serializer\Annotation as JMS;

/**
 * ##SWG\Definition(
 *     definition="Data\Properties\Streaming",
 *     description="Information about the streaming service.",
 *     required={"type", "url"}
 * )
 */
class Streaming
{
    const TYPE_YOUTUBE = 'youtube';
    const TYPE_DASH = 'dash';
    const TYPE_HLS = 'hls';

    const TYPES = [
        self::TYPE_DASH,
        self::TYPE_HLS,
        self::TYPE_YOUTUBE,
    ];

    /**
     * Type of the video stream.
     *
     * @var string
     * ##Assert\NotBlank()
     * ##Assert\Choice(
     *     callback="getTypes",
     *     multiple=false,
     * )
     * @JMS\Type("string")
     * ##SWG\Property(
     *     enum={"youtube", "dash", "hls"},
     *     example="youtube",
     * )
     */
    public $type;

    /**
     * URL of the video stream.
     *
     * @var string
     * ##Assert\Url()
     * ##Assert\NotBlank()
     * @JMS\Type("string")
     * ##SWG\Property(
     *     example="https://www.youtube.com/watch?v=M7g7Pfx6zjg",
     * )
     */
    public $url;

    /**
     * Get the available supported streaming types
     * 
     * @return array
     */
    public static function getTypes()
    {
        return self::TYPES;
    }
}
