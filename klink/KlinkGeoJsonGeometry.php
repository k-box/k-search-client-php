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
	 * getType
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}


	/**
	 * The coordinates of the geometry
	 * @var array
	 */

	public $coordinates;

	/**
	 * The coordinates of the geometry
	 * @return array
	 */
	public function getCoordinates() {
		return $this->coordinates;
	}

}