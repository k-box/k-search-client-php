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
	 * KlinkFacetItem[]
	 * @var KlinkFacetItem[]
	 */
	public $items = null;


	protected $min = 2;

	protected $count = 10;

	protected $filter = null;

	protected $prefix = null;

	/**
	 * @internal reserved for deserialization
	 */
	function __construct()
	{

	}


	/**
	 * Create a new facet instance
	 * 
	 * @param string $name   the name of the facet
	 * @param int $min Specify the minimun frequency for the facet-term to be return for the given, default 2
	 * @param string $prefix retrieve the facet items that have such prefix in the text 
	 * @param int $count  configure the number of terms to return for the given facet
	 * @param string $filter specify the filtering value to applied to the search for the given facet
	 */
	public static function create($name, $min = 2, $prefix = null, $count = 10, $filter = null)
	{
		$ret = new self;

		$ret->name = $name;
		$ret->min = $min;
		$ret->count = $count;
		$ret->filter = $filter;
		$ret->prefix = $prefix;

		return $ret;
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

	public function setMin($value){
		$this->min = $value;
		return $this;
	}

	public function setCount($value){
		$this->count = $value;
		return $this;
	}

	public function setFilter($value){
		$this->filter = $value;
		return $this;
	}

	public function setPrefix($value){
		$this->prefix = $value;
		return $this;
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
			'facet_'.$this->getName().'_count' => $this->getCount(),
			'facet_'.$this->getName().'_mincount' => $this->getMin(),
		);

		if(!is_null($this->getFilter())){
			$ser['filter_'.$this->getName()] = $this->getFilter();
		}

		if(!is_null($this->getPrefix())){
			$ser['facet_'.$this->getName().'_prefix'] = $this->getPrefix();
		}

		return $ser;

	}

}