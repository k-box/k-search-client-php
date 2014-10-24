<?php

/**
*  KlinkCoreClient.
*  offers a clean API to perform actions on the Klink Core
*/
final class KlinkCoreClient
{

/**
 TODO: magari usare le eccezioni per indicare che c'è stato un errore
 * */

	// ---- API endpoint constants


	/**
	 * DOCUMENTS_ENDPOINT
	 */

	const ALL_DOCUMENTS_ENDPOINT = 'documents/';


	/**
	 * SINGLE_DOCUMENT_ENDPOINT
	 */

	const SINGLE_DOCUMENT_ENDPOINT = 'document/{ID}';


	/**
	 * SEARCH_ENDPOINT
	 */

	const SEARCH_ENDPOINT = 'search/';


	/**
	 * AUTOCOMPLETE_ENDPOINT
	 */

	const AUTOCOMPLETE_ENDPOINT = 'autocomplete/';


	/**
	 * ALL_INSTITUTIONS_ENDPOINT
	 */

	const ALL_INSTITUTIONS_ENDPOINT = 'institutions/';


	/**
	 * SINGLE_INSTITUTION_ENDPOINT
	 */

	const SINGLE_INSTITUTION_ENDPOINT = 'institutions/{ID}';




	// ---- constructor and private fields

	/**
	 * Stores the institution identifier in the KLink network.
	 */
	// private $institution_id = null;

	// private $institution_auth = null;

	/**
	 * @var KlinkRestClient[];
	 * 
	 TODO: credo proprio che sarà una collection di RestClient, uno per ogni core specificato in fase di configurazione
	 */
	private /*KlinkRestClient*/ $rest = array();

	private $configuration = null;


	function __construct(KlinkConfiguration $config )
	{

		KlinkCoreClient::test($config); //test the configuration for errors



		// save the the configuration for the instance
		//$this->institution_id = $institution_id; //institution id can be saved only after first use

		$this->configuration = $config;

		/**
			TODO: initialize RestClient, one client for each core in the configuration
		*/

		foreach ($this->configuration->getCores() as $core) {

			$this->rest[] = new KlinkRestClient($core->core, $core);

		}

	}


	//a Document descriptor is required to specify its complete ID (that's composed of SiteID:DocumentID).



	// ----- Document interaction

	/**
	 * Description
	 * @param KlinkDocument $document 
	 * @param type $document_content 
	 * @return KlinkDocumentDescriptor|KlinkError
	 */
	function addDocument( KlinkDocument $document ){

		$conn = self::_get_connection();

		// $document->getDescriptor(), $document->getFile()

		return $conn->post( self::ALL_DOCUMENTS_ENDPOINT, $document->getDescriptor(), new KlinkDocumentDescriptor() );

	}

	/**
	 * Description
	 * @param KlinkDocumentDescriptor $document 
	 * @return boolean|KlinkError
	 */
	function removeDocument( KlinkDocumentDescriptor $document ){

		$conn = self::_get_connection();

		return $conn->delete( self::SINGLE_DOCUMENT_ENDPOINT, array('ID' => $document->getId()) );

	}

	/**
	 * Description
	 * @param KlinkDocument $document 
	 * @param type $document_content 
	 * @return KlinkDocumentDescriptor|KlinkError
	 */
	function updateDocument( KlinkDocument $document ){

		$rem = $this->removeDocument($document);
		if(KlinkHelpers::is_error($rem)){
			return $rem;
		}

		return $this->addDocument( $document );

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
	 * @return KlinkSearchResult returns the document that match the searched terms
	 */
	function search($terms, SearchType $type = null){

		if(is_null($type)){
			$type = KlinkSearchType::LOCAL;
		}

		$conn = self::_get_connection();

		return $conn->get(self::SEARCH_ENDPOINT, 
			array(
				'query' => $terms,
				'visibility' => $type == KlinkSearchType::LOCAL ? 'private' : 'public'
			), new KlinkSearchResult() );
	}


	// ----- Suggestions

	/**
	 * Give suggestion and autocomplete of the specified terms
	 * @param mixed $terms could be a string or a plain array. If an array is given each element is considered separately and completion for each terms are provided
	 * @param SearchType $type 
	 * @return string[] the possible suggestions
	 */
	function autocomplete($terms, SearchType $type = null){

		if(is_null($type)){
			$type = KlinkSearchType::LOCAL;
		}

		$conn = self::_get_connection();

		return $conn->getCollection(self::AUTOCOMPLETE_ENDPOINT, 
			array(
				'query' => $terms,
				'visibility' => $type == KlinkSearchType::LOCAL ? 'private' : 'public'
			), 'string');
		/**
			TODO: verificare che il return su array di stringhe possa funzionare
		*/
	}


	/**
	 * Updates the details of the specified institution
	 * @param KlinkInstitutionDetails $info 
	 * @return KlinkInstitutionDetails
	 */
	function updateInstitution(KlinkInstitutionDetails $info){
		$conn = self::_get_connection();

		return $conn->put( self::SINGLE_INSTITUTION_ENDPOINT, $info );
	}

	function saveInstitution(KlinkInstitutionDetails $info){
		
		$conn = self::_get_connection();

		return $conn->post( self::ALL_INSTITUTIONS_ENDPOINT, $info, 'KlinkInstitutionDetails' );

	}


	/**
	 * Get the Klink Institutions
	 * @param string $name optional for filtering institutions that contains the specified terms in the name
	 * @return KlinkInstitutionDetails[] the list of institutions
	 */
	function getInstitutions($name = null){

		$conn = self::_get_connection();

		$insts = $conn->getCollection( self::ALL_INSTITUTIONS_ENDPOINT, array(), 'KlinkInstitutionDetails' );

		if(!is_null($name)){

			/**
			 TODO: filtering
			 * */

			return $insts;
		}


		return $insts;
	}


	function getInstitution( $id ){

		$conn = self::_get_connection();

		return $conn->get( self::SINGLE_DOCUMENT_ENDPOINT, array('ID' => $id), new KlinkInstitutionDetails() );

	}

	
	// ----- Static Utility Stuff

	/**
	 * Test the specified KlinkConfiguration for errors
	 * */
	public static function test(KlinkConfiguration $config){

		/**
		 TODO: test the connection and the configuration with a simple call
		 create a client
		 do the test
		 if fails -> error
		 else -> ok
		 * */

	}


	// ----- Private Stuff


	/**
	 * Selects the Klink Core for the communication
	 * @return type
	 */
	private function _select_klink_core(){
		return 'id|url|something';
	}

	/**
	 * Get the connection to the Klink Core for performing the request
	 * @return KlinkRestClient to use
	 */
	private function _get_connection(){

		$core_id = self::_select_klink_core();

		return $this->rest[0];
	}
}