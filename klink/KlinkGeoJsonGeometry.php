<?php 

/**
* Define a location geometry according to the GeoJSON format for a geometry (point,...)
*/
final class KlinkGeoJsonGeometry
{
	
	/**
	 * The type of the geometry
	 * @var string
	 */
	public $type;

	/**
	 * The coordinates of the geometry
	 * @var array
	 */
	public $coordinates;
}