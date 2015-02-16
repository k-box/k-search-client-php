<?php

/**
* Describe a single facet
*/
final class KlinkFacet
{
	
	/**
	 * [$name description]
	 * @var string
	 */
	public $name = null;


	/**
	 * KlinkFacetItems[]
	 * @var KlinkFacetItems[]
	 */
	public $items = null;


	private $min = 2;

	private $count = 10;

	private $filter = null;

	private $prefix = null;

	/**
	 * Create a new facet instance
	 * 
	 * @param string $name   the name of the facet
	 * @param int $min Specify the minimun frequency for the facet-term to be return for the given, default 2
	 * @param string $prefix retrieve the facet items that have such prefix in the text 
	 * @param int $count  configure the number of terms to return for the given facet
	 * @param string $filter specify the filtering value to applied to the search for the given facet
	 */
	function __construct($name='', $min = 2, $prefix = null, $count = 10, $filter = null)
	{
		$this->name = $name;
		$this->min = $min;
		$this->count = $count;
		$this->filter = $filter;
		$this->prefix = $prefix;
	}

	public function getName(){
		return $this->name;
	}


	public function getMin(){
		return $this->min;
	}

	public function getCount(){
		return $this->count;
	}

	public function getFilter(){
		return $this->filter;
	}

	public function getPrefix(){
		return $this->prefix;
	}

	/**
	 * [getItems description]
	 * @return KlinkFacetItems[]
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * Convert the K-Link Core understandable parameters
	 * @return array
	 * @internal
	 */
	public function toKlinkParameter()
	{

		$ser = array(
			"facets" => $this->getName(),
			"facet_$this->getName()_count" => '',
			"facet_$this->getName()_mincount" => '',
			
		);

		if(!is_null($this->getFilter())){
			$ser["filter_$this->getName()"] = $this->getFilter();
		}

		if(!is_null($this->getPrefix())){
			$ser["facet_$this->getName()_prefix"] = $this->getPrefix();
		}

		return $ser;

	}

}