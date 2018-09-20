<?php

namespace KSearchClient\Model\Data;

use JMS\Serializer\Annotation as JMS;

/**
 * Represent a Geographic Geometry, as defined by the GeoJSON specification https://tools.ietf.org/html/rfc7946 
 * 
 * - The supported geometries are Point and Polygon
 * - It is assumed that the WGS84 coordinate system is used
 * - This class do not attempt to convert between various Reference Coordinate Systems
 */
class GeographicGeometry
{
    const TYPE_POINT = 'Point';
    const TYPE_POLYGON = 'Polygon';

    /**
     * The Universally unique identifier of this data.
     *
     * @var string
     * @JMS\Type("string")
     */
    public $type;

    /**
     * The URI where the source data is stored and retrievable.
     *
     * @var array
     * @JMS\Type("array")
     */
    public $coordinates;

    public function __construct($type = 'Point', $coordinates = [])
    {
        $this->type = $type;
        $this->coordinates = is_array($coordinates) ? $coordinates : [$coordinates];
    }

    public function __toString()
    {
        if(is_array($this->coordinates[0])){
            $internal = [];
            foreach ($this->coordinates as $component) {
                $internal[] = '[' . implode(',', $component) . ']';
            }
            $coordinates = '[' . implode(",", $internal) . ']';
        }
        else {
            $coordinates = implode(",", $this->coordinates);
        }

        return "{\"type\": \"$this->type\", \"coordinates\": [$coordinates]}";
    }

    /**
     * Create a Point geometry
     */
    public static function point($longitude, $latitude)
    {
        return new static(static::TYPE_POINT, [$longitude, $latitude]);
    }

    /**
     * Create a polygon geometry
     */
    public static function polygon(array $coordinates)
    {
        return new static(static::TYPE_POLYGON, $coordinates);
    }
}
