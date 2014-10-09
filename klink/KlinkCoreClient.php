<?php

/**
*  KlinkCoreClient.
*  offers a clean API to perform actions on the Klink Core
*/
final class KlinkCoreClient
{



	// ---- constructor and private fields

	/**
	 * Stores the institution identifier in the KLink network.
	 */
	private $institution_id = null;

	private $institution_auth = null;

	private /*KlinkRestClient*/ $rest = null;


	function __construct($institution_id, KlinkConfiguration $config )
	{
		# code...
		$this->institution_id = $institution_id;

		/**
			TODO: initialize RestClient
		*/
	}


	//a Document descriptor is required to specify its complete ID (that's composed of SiteID:DocumentID).



	// ----- Document interaction

	/**
	 * Description
	 * @param type IDocument $document 
	 * @param type $document_content 
	 * @return type
	 */
	function addDocument(KlinkDocumentDescriptor $document, $document_content){

	}

	/**
	 * Description
	 * @param type IDocument $document 
	 * @return boolean
	 */
	function removeDocument(KlinkDocumentDescriptor $document){

		return false;
	}

	/**
	 * Description
	 * @param type IDocument $document 
	 * @param type $document_content 
	 * @return type
	 */
	function updateDocument(KlinkDocumentDescriptor $document, $document_content){

		$this->removeDocument($document);

		$this->addDocument($document, $document_content);

	}

	/**
	 * Get the currently indexed documents that are local/private of the institution
	 * @return KlinkDocumentDescriptor[]
	 */
	function getLocalDocuments(){

		return null;
	}


	// ----- Search functionality

	/**
	 * Description
	 * @param string $terms the phrase or terms to search for
	 * @param SearchType $type the type of the search to be perfomed
	 * @return KlinkDocumentDescriptor[] returns the document that match the searched terms
	 */
	function search($terms, SearchType $type = KlinkSearchType::LOCAL){

		return null;
	}


	// ----- Suggestions

	/**
	 * Give suggestion and autocomplete of the specified terms
	 * @param mixed $terms could be a string or a plain array. If an array is given each element is considered separately and completion for each terms are provided
	 * @param SearchType $type 
	 * @return string[] the possible suggestions
	 */
	function autocomplete($terms, SearchType $type = KlinkSearchType::LOCAL){
		return null;
	}



	function saveInstitutionDetails(KlinkInstitutionDetails $info){

	}

}