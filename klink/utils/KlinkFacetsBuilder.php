<?php


/**
* Helps you constructing the facets search parameter with a fluent chainable api.
* 
* 
* To help the autocomplete a method is defined for all the supported facets.
* 
* You cannot call the same facet twice!
* 
* Remember to call @see build() at the end of the construction
* 
* PHP 5.6 non-sense warning why I would do that if I was able to put two version of the same method one for static and the other for instance
* if you have a call like this KlinkFacetsBuilder::documentType() and a warning/error like this:
* 	Non-static method KlinkFacetsBuilder::documentType() should not be called statically, assuming $this from incompatible context
* 
* you can do (new KlinkFacetsBuilder)->documentType() or KlinkFacetsBuilder::instance()->documentType 
* //this strange thing is caused by a particular way of using static inside PHP 5.6
*/
final class KlinkFacetsBuilder
{

	const DEFAULT_MINCOUNT = 2;

	const DEFAULT_COUNT = 10;

	/**
	 * Cache of known facets
	 */
	private $known_constants = null;

	/**
	 * Array of names of the facets already builded for check in case of the same facet is required to be builded two or more times
	 */
	private $already_builded = null; // array of constant names already used to test if they can initialize a facet twice

	/**
	 * The facets the where builded
	 * 
	 * @var KlinkFacet[]
	 */
	private $facets = array(); // the array of facets parameters

	
	function __construct()
	{
		$oClass = new ReflectionClass('KlinkFacet');

        $this->known_constants = $oClass->getConstants();

        $this->already_builded = array();

        $this->facets = array();
	}


	

	// A method for each facet type ---------------------------------------------------------------
	
	/**
	 * Facet for the document type
	 * - TO BE DOCUMENTED -
	 * 
	 * 
	 * @throws BadMethodCallException if called two or more times on the same builder
	 */
	public function documentType()
	{

		$isStatic = !(isset($this) && get_class($this) == __CLASS__); //This check is caused by the non-sense of PHP 5.6 that call the same method not considering the static modifier

		if(!$isStatic){
			$instance = $this;
		}
		else {
			$instance = new KlinkFacetsBuilder;
		}

		if(in_array(KlinkFacet::DOCUMENT_TYPE, $instance->already_builded)){
			throw new BadMethodCallException("The document type facet has been already added", 1);
		}

		$builded_params = call_user_func_array(array($instance, '_handle_facet_parameters'), func_get_args());

		$facet = null;

		if(is_null($builded_params)){
			$facet = KlinkFacet::create(KlinkFacet::DOCUMENT_TYPE, 1);
		}
		else {

			if(!is_null($builded_params['filter']) && !in_array($builded_params['filter'], KlinkDocumentUtils::getDocumentTypes())){
				throw new InvalidArgumentException("Invalid document type for filter", 2);
			}

			$facet = KlinkFacet::create(KlinkFacet::DOCUMENT_TYPE, 
						$builded_params['mincount'], 
						$builded_params['prefix'], 
						$builded_params['count'], 
						$builded_params['filter']);
		}

		$instance->facets[] = $facet;
		$instance->already_builded[] = KlinkFacet::DOCUMENT_TYPE;

		return $instance;
	}

	/**
	 * Facet for the document language
	 * - TO BE DOCUMENTED -
	 * 
	 * @throws BadMethodCallException if called two or more times on the same builder
	 */
	public function language()
	{

		$isStatic = !(isset($this) && get_class($this) == __CLASS__); //This check is caused by the non-sense of PHP 5.6 that call the same method not considering the static modifier

		if(!$isStatic){
			$instance = $this;
		}
		else {
			$instance = new KlinkFacetsBuilder;
		}

		if(in_array(KlinkFacet::LANGUAGE, $instance->already_builded)){
			throw new BadMethodCallException("The language facet has been already added", 1);
		}

		$builded_params = call_user_func_array(array($instance, '_handle_facet_parameters'), func_get_args());

		$facet = null;

		if(is_null($builded_params)){
			$facet = KlinkFacet::create(KlinkFacet::LANGUAGE, 1);
		}
		else {

			$facet = KlinkFacet::create(KlinkFacet::LANGUAGE, 
						$builded_params['mincount'], 
						$builded_params['prefix'], 
						$builded_params['count'], 
						$builded_params['filter']);
		}

		$instance->facets[] = $facet;
		$instance->already_builded[] = KlinkFacet::LANGUAGE;

		return $instance;
	}

	/**
	 * Facet for the institution id
	 * - TO BE DOCUMENTED -
	 * 
	 * variable number of parameters
	 * 
	 * if NONE   => enable the facet will 
	 * if one string => the filter (check if is a valid institution id)
	 * if one int => number of items to return for the facet (count)
	 * if two ints => 1: count, 2: mincount
	 * if 3 => 1: filter, 2: count, 3: mincount
	 * 
	 * @throws BadMethodCallException if called two or more times on the same builder
	 */
	public function institution()
	{
		$isStatic = !(isset($this) && get_class($this) == __CLASS__); //This check is caused by the non-sense of PHP 5.6 that call the same method not considering the static modifier

		if(!$isStatic){
			$instance = $this;
		}
		else {
			$instance = new KlinkFacetsBuilder;
		}

		if(in_array(KlinkFacet::INSTITUTION_ID, $instance->already_builded)){
			throw new BadMethodCallException("The institution facet has been already added", 1);
		}

		$builded_params = call_user_func_array(array($instance, '_handle_facet_parameters'), func_get_args());

		$facet = null;

		if(is_null($builded_params)){
			$facet = KlinkFacet::create(KlinkFacet::INSTITUTION_ID, 1);
		}
		else {

			if(!is_null($builded_params['filter'])){
				KlinkHelpers::is_valid_id($builded_params['filter'], 'filter');
			}

			$facet = KlinkFacet::create(KlinkFacet::INSTITUTION_ID, 
						$builded_params['mincount'], 
						$builded_params['prefix'], 
						$builded_params['count'], 
						$builded_params['filter']);
		}

		$instance->facets[] = $facet;
		$instance->already_builded[] = KlinkFacet::INSTITUTION_ID;

		return $instance;
	}

	/**
	 * @see institution()
	 */
	public function institutionId(){

		$isStatic = !(isset($this) && get_class($this) == __CLASS__); //This check is caused by the non-sense of PHP 5.6 that call the same method not considering the static modifier

		if(!$isStatic){
			$instance = $this;
		}
		else {
			$instance = new KlinkFacetsBuilder;
		}

		return call_user_func_array(array($instance, 'institution'), func_get_args());
	}


	// public function __call($name, $arguments)
	// {
	// 	// if an unknown facet is called, but is defined as constant construct a base instance for the query with default values


	// 	print_r(array('call' => $name, 'arguments' => $arguments));
		
	// 	return false;
	// }


	// Final Build --------------------------------------------------------------------------------


	/**
	 * The final method. Builds the facets parameters to pass to search or specific functions that requires an array of KlinkFacet
	 * 
	 * @return KlinkFacet[] the array of facets
	 */
	public function build()
	{
		return $this->facets;
	}



	// Helpers ------------------------------------------------------------------------------------

	/**
	 * if NONE   => return null
	 * if one string => the filter (check if is a valid institution id)
	 * if one int => number of items to return for the facet (count)
	 * if two ints => 1: count, 2: mincount
	 * if 3 => 1: filter, 2: count, 3: mincount
	 */
	private function _handle_facet_parameters()
	{

		$default = array('filter' => null, 'mincount' => self::DEFAULT_MINCOUNT, 'count' => self::DEFAULT_COUNT, 'prefix' => null);

	    if (func_num_args() == 0) {

	    	return null;

	    }

	    $num_args = func_num_args();

	    if(func_num_args() == 1 && empty($num_args)){
	    	return null;
	    }

	    if (func_num_args() == 1 && is_string(func_get_arg(0))) {

	    	return array_merge( $default, array('filter' => func_get_arg(0)) );

	    }
	    else if (func_num_args() == 1 && is_integer(func_get_arg(0))) {

	    	return array_merge( $default, array('count' => func_get_arg(0)) );
	    	
	    }
	    else if(func_num_args() == 2 && func_get_args() === array_filter(func_get_args(), 'is_int')){
	    	// only ints

	    	return array_merge( $default, array('count' => func_get_arg(0), 'mincount' => func_get_arg(1)) );
	    }
	    else if(func_num_args() == 3){

	    	$args = func_get_args();
	    	$splice = array_splice($args, 1);

	    	if(is_string(func_get_arg(0)) && $splice === array_filter($splice, 'is_int')) {
		    	return array_merge( $default, array('filter' => func_get_arg(0), 'count' => func_get_arg(1), 'mincount' => func_get_arg(2)) );	
		    }

	    }

	    throw new BadMethodCallException("Bad parameters", -42);
	}


	protected function _all()
	{

		$instance = $this;

		foreach ($this->known_constants as $name => $facetName) {

			$instance = call_user_func_array(array($this, $facetName), array());

		}

		return $this->build();
	}


	protected function _allNames()
	{
		return array_values( $this->known_constants );
	}




	// Static facilities for start building -------------------------------------------------------

	/**
	 * Enable the first static call for each facet method available on an instance of KlinkFacetsBuilder
	 * 
	 * example
	 * 
	 * KlinkFacetsBuilder::documentType() will create an instance of KlinkFacetsBuilder and call documentType()
	 * 
	 * 
	 * @throws BadMethodCallException if the builder don't have a facet specific instance method
	 */
	public static function __callStatic($method, $arguments)
	{

		$s = new KlinkFacetsBuilder();

		if(method_exists($s, $method)){

			return call_user_func_array(array($s, $method), $arguments);

		}

		throw new BadMethodCallException("Call to undefined method KlinkFacetsBuilder::{$method}()");

	}


	/**
	 * Return all the Klink supported facets with the default configuration
	 * 
	 * @return KlinkFacet[] array of the available KlinkFacet
	 */
	public static function all(){

		$s = new KlinkFacetsBuilder();

		return $s->_all();

	}

	/**
	 * Create a new instance of KlinkFacetsBuilder from a static context.
	 * 
	 * This is here only to cope with PHP 5.6 that consider a method without static modifier equal to a method with static modifier
	 * 
	 * @return KlinkFacetsBuilder
	 */
	public static function instance(){

		return new KlinkFacetsBuilder();

	}

	/**
	 * Create a new instance of KlinkFacetsBuilder from a static context.
	 * 
	 * This is here only to cope with PHP 5.6 that consider a method without static modifier equal to a method with static modifier
	 * 
	 * @return KlinkFacetsBuilder
	 */
	public static function create(){

		return new KlinkFacetsBuilder();

	}

	/**
	 * Create a new instance of KlinkFacetsBuilder from a static context.
	 * 
	 * This is here only to cope with PHP 5.6 that consider a method without static modifier equal to a method with static modifier
	 * 
	 * @return KlinkFacetsBuilder
	 */
	public static function i(){

		return new KlinkFacetsBuilder();

	}

	/**
	 * Return the names of the currently supported facets
	 * 
	 * @return array array of strings
	 */
	public static function allNames(){

		$s = new KlinkFacetsBuilder();

		return $s->_allNames();

	}
}