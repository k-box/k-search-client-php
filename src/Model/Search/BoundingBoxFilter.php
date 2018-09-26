<?php

namespace KSearchClient\Model\Search;

use JMS\Serializer\Annotation as JMS;
use KSearchClient\Model\Data\GeographicGeometry;

/**
 * Filter for geographic location based on a bounding box
 */
class BoundingBoxFilter
{
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
	public $bounding_box = null;


	/**
	 * Create a bounding box filter
	 * 
	 * @param string|GeographicGeometry $bounding_box
	 */
	public function __construct($bounding_box = null)
	{
		$this->bounding_box = $bounding_box instanceof GeographicGeometry ? $bounding_box->__toString() : $bounding_box;
	}
	
}