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
	 * The list of clients that can connect to a KLink Core
	 * 
	 * @var KlinkRestClient[];
	 */
	private $rest = array();

	/**
	 * Stores the configuration for this KlinkCoreClient instance
	 */
	private $configuration = null;


	function __construct(KlinkConfiguration $config )
	{

		KlinkCoreClient::test($config); //test the configuration for errors

		$this->configuration = $config;

		foreach ($this->configuration->getCores() as $core) {

			$this->rest[] = new KlinkRestClient($core->getCore(), $core);

		}

	}


	//a Document descriptor is required to specify its complete ID (that's composed of SiteID:DocumentID).



	// ----- Document interaction

	/**
	 * Description
	 * @param KlinkDocument $document 
	 * @param type $document_content 
	 * @return KlinkDocumentDescriptor
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function addDocument( KlinkDocument $document ){

		$conn = self::_get_connection();

		$array = array(
			'descriptor' => $document->getDescriptor(),
			'documentData' => $document->getDocumentData(),
		);

		$rem = $conn->post( self::ALL_DOCUMENTS_ENDPOINT, $array, new KlinkDocumentDescriptor() );

		if(KlinkHelpers::is_error($rem)){
			throw new KlinkException((string)$rem);
		}

		return $rem;

	}

	/**
	 * Description
	 * @param KlinkDocumentDescriptor $document 
	 * @return boolean
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function removeDocument( KlinkDocumentDescriptor $document ){

		if( !KlinkDocumentUtils::isLocalDocument( $document, $this->configuration->getInstitutionId(), $this->configuration->getAdapterId() ) ){
			throw new KlinkException("You cannot remove document you don't own");
		}

		$conn = self::_get_connection();

		$rem = $conn->delete( self::SINGLE_DOCUMENT_ENDPOINT, array('ID' => $document->getId()) );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem );
		}

		return $rem;

	}

	/**
	 * Description
	 * @param KlinkDocument $document the new information about the document. The document descriptor must have the same ID of the already existing document
	 * @param type $document_content 
	 * @return KlinkDocumentDescriptor
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function updateDocument( KlinkDocument $document ){

		$rem = $this->removeDocument($document);

		if(KlinkHelpers::is_error( $rem )){
			throw new KlinkException( (string)$rem );
		}

		$rem = $this->addDocument( $document );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem );
		}

		return $rem;

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
	 * @param SearchType $type the type of the search to be perfomed, if null is specified the default behaviour is KlinkSearchType::GLOBAL
	 * @param int $resultsPerPage the number of results per page
	 * @param int $page the page to display
	 * @return KlinkSearchResult returns the document that match the searched terms
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function search($terms, KlinkSearchType $type = null, $resultsPerPage = 10, $page = 0){

		if(is_null($type)){
			$type = KlinkSearchType::KLINK_PUBLIC;
		}

		$conn = self::_get_connection();

		$rem = $conn->get(self::SEARCH_ENDPOINT, 
			array(
				'query' => $terms,
				'visibility' => $type
			), new KlinkSearchResult() );

		if(KlinkHelpers::is_error($rem)){
			throw new KlinkException((string)$rem);
		}

		return $rem;
	}


	// ----- Suggestions

	/**
	 * Give suggestion and autocomplete of the specified terms
	 * @param mixed $terms could be a string or a plain array. If an array is given each element is considered separately and completion for each terms are provided
	 * @param SearchType $type 
	 * @return string[] the possible suggestions
	 * @throws KlinkException if something wrong happened during the communication with the core
	 * @internal Reserved for future uses
	 */
	function autocomplete($terms, KlinkSearchType $type = null){

		if(is_null($type)){
			$type = KlinkSearchType::KLINK_PUBLIC;
		}

		$conn = self::_get_connection();

		return $conn->getCollection(self::AUTOCOMPLETE_ENDPOINT, 
			array(
				'query' => $terms,
				'visibility' => $type
			), 'string');
		/**
			TODO: verificare che il return su array di stringhe possa funzionare
		*/
	}


	/**
	 * Updates the details of the specified institution
	 * @param KlinkInstitutionDetails $info 
	 * @return KlinkInstitutionDetails
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function updateInstitution(KlinkInstitutionDetails $info){
		$conn = self::_get_connection();

		$rem = $conn->put( self::SINGLE_INSTITUTION_ENDPOINT, $info );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem );
		}

		return $rem;
	}

	/**
	 * Description
	 * @param type KlinkInstitutionDetails $info 
	 * @return type
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function saveInstitution(KlinkInstitutionDetails $info){
		
		$conn = self::_get_connection();

		$rem = $conn->post( self::ALL_INSTITUTIONS_ENDPOINT, $info, 'KlinkInstitutionDetails' );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem );
		}

		return $rem;

	}


	/**
	 * Get the Klink Institutions
	 * @param string $name optional for filtering institutions that contains the specified terms in the name
	 * @return KlinkInstitutionDetails[] the list of institutions
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function getInstitutions($nameOrId = null){

		$conn = self::_get_connection();

		$insts = $conn->getCollection( self::ALL_INSTITUTIONS_ENDPOINT, array(), 'KlinkInstitutionDetails' );

		if( KlinkHelpers::is_error( $insts ) ){
			throw new KlinkException( (string)$insts );
		}

		if( !is_null( $nameOrId ) ){

			/**
			 TODO: filtering
			 * */

			return $insts;
		}


		return $insts;
	}

	/**
	 * Description
	 * @param type $id 
	 * @return type
	 * @throws KlinkException if something wrong happened during the communication with the core
	 * @throws IllegalArgumentException if the id is not well formatted
	 */
	function getInstitution( $id ){

		$conn = self::_get_connection();

		KlinkHelpers::is_valid_id( $id );

		$rem = $conn->get( self::SINGLE_DOCUMENT_ENDPOINT, array('ID' => $id), new KlinkInstitutionDetails() );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem );
		}

		return $rem;

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
	 * Get the connection to the Klink Core for performing the request.
	 * The connection is selected considering last execution time if more than one Cores are configured.
	 * 
	 * @return KlinkRestClient the KlinkRestClient to use
	 */
	private function _get_connection(){

		$core_id = self::_select_klink_core();

		if(count($this->rest) == 1){
			return $this->rest[0];
		}

		return $this->rest[0];
	}
}