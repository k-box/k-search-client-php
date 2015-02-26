<?php

/**
* Describe a location entry
*/
final class KlinkLocationDescriptor
{

	/**
	 * The name of the location (could be in english or russian)
	 * @var string
	 */

	public $label;

	/**
	 * The name of the location (could be in english or russian)
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}


	/**
	 * The GeoJSON object that represents the centroid of the village/town/country/
	 * @var KlinkGeoJsonGeometry
	 */

	public $centroid;

	/**
	 * The GeoJSON object that represents the centroid of the village/town/country/
	 * @return KlinkGeoJsonGeometry
	 */
	public function getCentroid() {
		return $this->centroid;
	}


}