<?php

/**
 * Define the search result structure
 * 
 * @package Klink
 */
final class KlinkSearchResult
{
	


	/**
	 * The original query given at API invocation
	 * @var string
	 */

	public $query;

	/**
	 * getQuery
	 * @return string
	 */
	public function getTerms() {
		return $this->query;
	}

	/**
	 * visibility
	 * @var KlinkVisibilityType
	 * @preferset
	 */

	public $visibility;

	/**
	 * setVisibility
	 * @param string $value
	 * @return void
	 */
	public function setVisibility($value) {

		if( is_string( $value ) ) {
			$value = KlinkVisibilityType::fromString( $value );
		}

		$this->visibility = $value;
	}

	/**
	 * The performed search visibility (public or private)
	 * @return KlinkVisibilityType
	 */
	public function getVisibility() {
		return $this->visibility;
	}

	/**
	 * numFound
	 * @var int
	 */

	public $numFound;

	/**
	 * The grand total of results matched by the query
	 * @return int
	 */
	public function getTotalResults() {
		return $this->numFound;
	}

	/**
	 * queryTime
	 * @var float
	 */

	public $queryTime;

	/**
	 * Search execution time (in milliseconds)
	 * @return int
	 */
	public function getSearchTime() {
		return $this->queryTime;
	}

	/**
	 * specify the number of results to retrieve, if no value is given the default value of 10 is used
	 * @var int
	 */

	public $numResults;

	/**
	 * specify the number of results to retrieve, if no value is given the default value of 10 is used
	 * @return int
	 */
	public function getResultsPerPage() {
		return $this->numResults;
	}

	/**
	 * specify the first result to return from the complete set of retrieved set, the value is 0-based; the default value is 0
	 * @var int
	 */

	public $startResult;

	/**
	 * specify the first result to return from the complete set of retrieved set, the value is 0-based; the default value is 0
	 * @return int
	 */
	public function getOffset() {
		return $this->startResult;
	}

	/**
	 * The number of results returned by this invocation
	 * @var int
	 */

	public $itemCount;

	/**
	 * The number of results returned by this invocation
	 * @return int
	 */
	public function getCurrentResultCount() {
		return $this->itemCount;
	}

	/**
	 * The current list of Results
	 * @var KlinkDocumentDescriptor[]
	 */

	public $items;

	/**
	 * The current list of Results
	 * @return KlinkDocumentDescriptor[]
	 */
	public function getResults() {
		return $this->items;
	}






	function __construct($query = '', $queryTime = '', $numFound = 20, $itemsCount=10)
	{
		$this->numResults = 10;
		$this->startResult = 0;
		$this->visibility = KlinkVisibilityType::KLINK_PUBLIC;
		$this->query = $query;
		$this->queryTime = $queryTime;
		$this->numFound = $numFound;
		$this->itemCount = $itemsCount;
		$this->items = array(

			new KlinkDocumentDescriptor('K-a11', 'K', 'a11', KlinkDocumentUtils::generateHash('test'))

			);
	}


	/**
	 * for json serialization purposes
	 * @return type
	 */
	public function to_array(){
		$json = array();
	    foreach($this as $key => $value) {
	        if(is_array($value)){
	        	$json[$key] = array();

	        	foreach ($value as $v) {
	        			
	        		//if has to_array I call it

	        		//otherwise just a json encode



	        	}

	    		
	    	}
	    	else {
		        $json[$key] = $value;
		    }
	    }
	    return $json; // or json_encode($json)
	}



}