<?php 

/**
* Define a location Feature according to the GeoJSON specification
*/
final class KlinkGeoJsonFeature
{

	/**
	 * The type of the GeoJson object
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
	 * The geometry of the feature
	 * @var KlinkGeoJsonGeometry
	 */

	public $geometry;

	/**
	 * The geometry of the feature
	 * @return KlinkGeoJsonGeometry
	 */
	public function getGeometry() {
		return $this->geometry;
	}


	/**
	 * [$properties description]
	 */
	public $properties;

	/**
	 * The properties associated to the feature.
	 *
	 * Properties are expressed as key => value.
	 *
	 * Possible properties are:
	 * - name: The name of the city/village/country (e.g. "Kyrgyzstan")
     * - countryCode: the two letters code of the country (e.g. "KG")
	 * 
	 * @return [type] [description]
	 */
	public function getProperties()
	{
		return $this->properties;
	}



	/**
	 * Construct an instance of GeoJson Feature for the locations attribute of the @see KlinkDocumentDescriptor
	 * 
	 * @param  KlinkGeoJsonGeometry $geometry The geometry of the feature.
	 * @param  array $properties The properties attached to the feature.
	 * @return KlinkGeoJsonFeature the instance of the feature
	 */
	public static function create($geometry, $properties = null){

		$inst = new KlinkGeoJsonFeature;

		$inst->geometry = $geometry;

		$inst->properties = $properties;

		$inst->type = "Feature";

		return $inst;

	}

}