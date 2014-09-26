<?php

/**
*  KlinkCoreClient.
*  offers a clean API to perform actions on the Klink Core
*/
class KlinkCoreClient
{



	// ---- constructor and private fields

	/**
	 * Stores the institution identifier in the KLink network.
	 */
	private $institution_id = null;

	function __construct($institution_id, $core_api_url = "https://localhost/kcore/", )
	{
		# code...
		$this->institution_id = $institution_id;
	}


	//a Document descriptor is required to specify its complete ID (that's composed of SiteID:DocumentID).



	// ----- Document interaction

	/**
	 * Description
	 * @param type IDocument $document 
	 * @param type $document_content 
	 * @return type
	 */
	function addDocument(IDocument $document, $document_content){

	}

	/**
	 * Description
	 * @param type IDocument $document 
	 * @return boolean
	 */
	function removeDocument(IDocument $document){

		return false;
	}

	/**
	 * Description
	 * @param type IDocument $document 
	 * @param type $document_content 
	 * @return type
	 */
	function updateDocument(IDocument $document, $document_content){

		$this->removeDocument($document);

		$this->addDocument($document, $document_content);

	}

	/**
	 * Get the currently indexed documents that are local/private of the institution
	 * @return IDocument[]
	 */
	function getLocalDocuments(){

		return null;
	}


	// ----- Search functionality

	/**
	 * Description
	 * @param string $terms the phrase or terms to seach for
	 * @param SearchType $type the type of the search to be perfomed
	 * @return IDocument[] returns the document that match the searched terms
	 */
	function search($terms, SearchType $type = SearchType::LOCAL){

		return null;
	}


	// ----- Suggestions

	/**
	 * Give suggestion and autocomplete of the specified terms
	 * @param mixed $terms could be a string or a plain array. If an array is given each element is considered separately and completion for each terms are provided
	 * @param SearchType $type 
	 * @return string[] the possible suggestions
	 */
	function autocomplete($terms, SearchType $type = SearchType::LOCAL){
		return null;
	}
}