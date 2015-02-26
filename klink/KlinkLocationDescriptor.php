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
	 * The GeoJSON object that represents the centroid of the village/town/country/
	 * @var KlinkGeoJsonGeometry
	 */
	public $centroid;



	function __construct()
	{
		
	}
}