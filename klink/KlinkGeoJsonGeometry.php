<?php 

/**
* Define a location geometry according to the GeoJSON format for a geometry (point,...)
*/
final class KlinkGeoJsonGeometry
{

	/**
	 * The type for a Point (two coordinates)
	 */
	const TYPE_POINT = 'Point';

	/**
	 * The type for mutiple points in the same geometry
	 */
	const TYPE_MULTIPOINT = 'MultiPoint';

	/**
	 * The type for a line geometry
	 */
	const TYPE_LINESTRING = 'LineString';
	
	/**
	 * The type for a Polygon geometry
	 */
	const TYPE_POLYGON = 'Polygon';


	/**
	 * The type of the geometry
	 * @var string
	 */
	public $type;

	/**
	 * The type of the geometry
	 *
	 * Could be: Point, Polygon
	 * 
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


	/**
	 * Create a Geodetic Point geometry for describing a point on the Earth surface.
	 * 
	 * @param  float $latitude  The latitude of the point expressed in decimal degress according to the WGS84 elipsoid.
	 * @param  float $longitude The longitude of the point expressed in decimal degress according to the WGS84 elipsoid.
	 * @return KlinkGeoJsonGeometry 
	 */
	public static function createPoint($latitude, $longitude, $altitude = null){

		return self::create(array_filter(array($latitude, $longitude, $altitude)));

	}

	/**
	 * Creates a generic geometry.
	 * 
	 * @param  array $coordinates The coordinates of the geometry
	 * @param  string $type       The type of the geometry (default KlinkGeoJsonGeomtetry::TYPE_POINT). See the KlinkGeoJsonGeomtetry constants for other types
	 * @return KlinkGeoJsonGeometry
	 */
	public static function create($coordinates, $type = null){

		$instance = new KlinkGeoJsonGeometry;

		$instance->type = is_null($type) ? KlinkGeoJsonGeometry::TYPE_POINT : $type;

		$instance->coordinates = $coordinates;

		return $instance;

	}

}