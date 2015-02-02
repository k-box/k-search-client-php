<?php

if( !defined( 'KLINKADAPTER_DEBUG' ) ){
	define( 'KLINKADAPTER_DEBUG', false );
}

if( !defined( 'KLINK_BOILERPLATE_VERSION' ) ){
	define( 'KLINK_BOILERPLATE_VERSION', '0.2' );
}

/**
*  KlinkCoreClient.
*  offers a clean API to perform actions on the Klink Core
*/
final class KlinkCoreClient
{

	// ---- API endpoint constants


	/**
	 * DOCUMENTS_ENDPOINT
	 */
	const ALL_DOCUMENTS_ENDPOINT = 'descriptors/';


	/**
	 * SINGLE_DOCUMENT_ENDPOINT
	 */
	const SINGLE_DOCUMENT_ENDPOINT = 'descriptors/{VISIBILITY}/{INSTITUTION_ID}/{LOCAL_DOC_ID}';


	/**
	 * SEARCH_ENDPOINT
	 */
	const SEARCH_ENDPOINT = 'search/{VISIBILITY}/';


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

	/**
	 * The URL of the standalone Thumbnail generator API.
	 */
	const THUMBNAIL_GENERATOR_URL = 'http://klink-thumb2.azurewebsites.net/api/thumb/';

	const THUMBAIL_GENERATOR_AUTHENTICATION = 'klink:#8e44ad';



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

    /**
     * Stores the (optional) Telemetry object
     */
    private $telemeter=null;


	function __construct( KlinkConfiguration $config, IKlinkCoreTelemeter $telemeter=null )
	{

		//KlinkCoreClient::test($config); //test the configuration for errors

		$this->telemeter= $telemeter;

        $this->configuration = $config;

		foreach ($this->configuration->getCores() as $core) {

			$this->rest[] = new KlinkRestClient($core->getCore(), $core, array('debug' => $this->configuration->isDebugEnabled()));

		}

	}


	// ----- Document interaction

	/**
	 * Add a document to the K-Link Core.
	 * 
	 * @param KlinkDocument $document 
	 * @param type $document_content 
	 * @return KlinkDocumentDescriptor
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function addDocument( KlinkDocument $document ){

		$conn = self::_get_connection();

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);


		$array = array(
			'descriptor' => $document->getDescriptor(),
			'documentData' => $document->getDocumentData(),
		);

		$rem = $conn->post( self::ALL_DOCUMENTS_ENDPOINT, $array, new KlinkDocumentDescriptor() );

		if(KlinkHelpers::is_error($rem)){
			throw new KlinkException((string)$rem, $rem->get_error_data_code());
		}

        if($this->telemeter!=null) $this->telemeter->afterOperation($conn->getUrl(),__FUNCTION__);

		return $rem;

	}

	/**
	 * Removes a previously added document given it's KlinkDocumentDescriptor 
	 * @param KlinkDocumentDescriptor $document 
	 * @return boolean
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function removeDocument( KlinkDocumentDescriptor $document ){

		if( !KlinkDocumentUtils::isLocalDocument( $document, $this->configuration->getInstitutionId(), $this->configuration->getAdapterId() ) ){
			throw new KlinkException("You cannot remove document you don't own");
		}


		$conn = self::_get_connection();

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);

		$rem = $conn->delete( self::SINGLE_DOCUMENT_ENDPOINT, 
			array(
				'VISIBILITY' => $document->getVisibility(),
				'INSTITUTION_ID' => $document->getInstitutionID(),
				'LOCAL_DOC_ID' => $document->getLocalDocumentID(),
				) 
			);

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

        if($this->telemeter!=null) $this->telemeter->afterOperation($conn->getUrl(),__FUNCTION__);

		return $rem;

	}

	/**
	 * Remove a document given it's institution and local document identifier plus the it's visibility.
	 *
	 * If the visibility is not specified a KlinkVisibilityType::KLINK_PUBLIC is assumed
	 * 
	 * Performs a document removal given directly the institutions identifier and the local document identifier.
	 * 
	 * @param string $institution the institution identifier
	 * @param string $document the local document identifier
	 * @param string $visibility (optional) The visibility of the document to be retrieved. Acceptable values are: public, private. Default value KlinkVisibilityType::KLINK_PUBLIC.
	 * @return boolean
	 * @throws InvalidArgumentException If one or more parameters are invalid
	 * @internal
	 */
	function removeDocumentById( $institution, $document, $visibility = null ){

		if( $institution !== $this->configuration->getInstitutionId() ){
			throw new KlinkException("You cannot remove document you don't own");
		}

		if(is_null($visibility)){
			$visibility = KlinkVisibilityType::KLINK_PUBLIC;
		}
		else {
			$visibility = KlinkVisibilityType::fromString($visibility);
		}

		$conn = self::_get_connection();

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);


		$rem = $conn->delete( self::SINGLE_DOCUMENT_ENDPOINT, 
			array(
				'VISIBILITY' => $visibility,
				'INSTITUTION_ID' => $institution,
				'LOCAL_DOC_ID' => $document,
				) 
			);

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

        if($this->telemeter!=null) $this->telemeter->afterOperation($conn->getUrl(),__FUNCTION__);

		return $rem;

	}

	/**
	 * Updates a previously added document. 
	 *
	 * An existing KlinkDocumentDescriptor must be provided.
	 * 
	 * @param KlinkDocument $document the new information about the document. The document descriptor must have the same ID of the already existing document
	 * @param type $document_content 
	 * @return KlinkDocumentDescriptor
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function updateDocument( KlinkDocument $document ){

		$rem = $this->removeDocument($document->getDescriptor());

		if(KlinkHelpers::is_error( $rem )){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

		$rem = $this->addDocument( $document );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

		return $rem;

	}

	/**
	 * Retrieve the Document Descriptor of an indexed document given the institution identifier and the local document identifier
	 * @param string $institutionId 
	 * @param string $documentId 
	 * @param string $visibility (optional) The visibility of the document to be retrieved. Acceptable values are: public, private. Default value KlinkVisibilityType::KLINK_PUBLIC.
	 * @return KlinkDocumentDescriptor
	 * @throws InvalidArgumentException If one or more parameters are invalid
	 */
	function getDocument( $institutionId, $documentId, $visibility = null ){

		$conn = self::_get_connection();

		if(is_null($visibility)){
			$visibility = KlinkVisibilityType::KLINK_PUBLIC;
		}
		else {
			$visibility = KlinkVisibilityType::fromString($visibility);
		}

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);

		KlinkHelpers::is_valid_id( $institutionId, 'institution id' );
		KlinkHelpers::is_valid_id( $documentId, 'local document id' );

		$rem = $conn->get( self::SINGLE_DOCUMENT_ENDPOINT, new KlinkDocumentDescriptor(), array(
			'VISIBILITY' => $document->getVisibility(),
			'INSTITUTION_ID' => $institutionId,
			'LOCAL_DOC_ID' => $documentId) );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

        if($this->telemeter!=null) $this->telemeter->afterOperation($conn->getUrl(),__FUNCTION__);

		return $rem;

	}



	// ----- Search functionality

	/**
	 * Performs a search over KLink.
	 * 
	 * Execute a KLink search on the reference KLink Core. The performed search reflects the specified parameters.
	 * 
	 * @param string $terms the phrase or terms to search for
	 * @param KlinkSearchType $type the type of the search to be perfomed, if null is specified the default behaviour is KlinkSearchType::KLINK_PUBLIC
	 * @param int $resultsPerPage the number of results per page
	 * @param int $offset the page to display
	 * @return KlinkSearchResult returns the document that match the searched terms
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function search( $terms, $type = null, $resultsPerPage = 10, $offset = 0 ){

		if(is_null($type)){
			$type = KlinkSearchType::KLINK_PUBLIC;
		}

		$conn = self::_get_connection();

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);

		$rem = $conn->get( self::SEARCH_ENDPOINT, new KlinkSearchResult(),
			array(
				'VISIBILITY' => $type,
				'query' => $terms,
				'numResults' => $resultsPerPage,
				'startResult' => $offset
			) );

		if(KlinkHelpers::is_error($rem)){
			throw new KlinkException((string)$rem, $rem->get_error_data_code());
		}

        if($this->telemeter!=null) $this->telemeter->afterOperation($conn->getUrl(),__FUNCTION__);

		return $rem;
	}


	// ----- Suggestions

	/**
	 * Give suggestion and autocomplete of the specified terms
	 * @param mixed $terms could be a string or a plain array. If an array is given each element is considered separately and completion for each terms are provided
	 * @param string $type KlinkSearchType::KLINK_PUBLIC or KlinkSearchType::KLINK_PRIVATE
	 * @return string[] the possible suggestions
	 * @throws KlinkException if something wrong happened during the communication with the core
	 * @internal Reserved for future uses
	 */
	function autocomplete( $terms, $type = null ){

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
	 * Delete an institution from the K-Link Core
	 * 
	 * @param  string $id The institution identifier
	 * @return boolean     true in case the institution has been succesfully removed
	 * @throws KlinkException If the K-Link Core returned an error
	 */
	public function deleteInstitution( $id )
	{

		$conn = self::_get_connection();

		$rem = $conn->delete( self::SINGLE_INSTITUTION_ENDPOINT, 
			array(
				'ID' => $id,
				) 
			);

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

		return $rem;
	}

	/**
	 * Updates the details of the specified institution
	 * @param KlinkInstitutionDetails $info 
	 * @return KlinkInstitutionDetails
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function updateInstitution( KlinkInstitutionDetails $info ){

		$rem = $this->deleteInstitution( $info->getID() );

		if(KlinkHelpers::is_error( $rem )){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

		$rem = $this->saveInstitution( $info );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

		return $rem;
	}

	/**
	 * Saves a new institution into the K-Link Core
	 * 
	 * @param type KlinkInstitutionDetails $info 
	 * @return type
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function saveInstitution( KlinkInstitutionDetails $info ){
		
		$conn = self::_get_connection();

		$rem = $conn->post( self::ALL_INSTITUTIONS_ENDPOINT, $info, 'KlinkInstitutionDetails' );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

		return $rem;

	}


	/**
	 * Get the Klink Institutions
	 * @param string $name optional for filtering institutions that contains the specified terms in the name
	 * @return KlinkInstitutionDetails[] the list of institutions
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function getInstitutions( $nameOrId = null ){

		$conn = self::_get_connection();

		$insts = $conn->getCollection( self::ALL_INSTITUTIONS_ENDPOINT, array(), 'KlinkInstitutionDetails' );

		if( KlinkHelpers::is_error( $insts ) ){
			throw new KlinkException( (string)$insts, $insts->get_error_data_code() );
		}

		if( !is_null( $nameOrId ) && count( $insts ) > 0 ){

			$filtered = array();

			foreach ($insts as $i) {
				
				if( $i->getID() === $nameOrId || strpos( $i->getName(), $nameOrId ) !== false ){
					$filtered[] = $i;
				}

			}

			return $filtered;
		}


		return $insts;
	}

	/**
	 * Get the institutions details.
	 * 
	 * @param string $id the institution identifier
	 * @return KlinkInstitutionDetails
	 * @throws KlinkException if something wrong happened during the communication with the core
	 * @throws IllegalArgumentException if the id is not well formatted
	 */
	function getInstitution( $id ){

		$conn = self::_get_connection();

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);

		KlinkHelpers::is_valid_id( $id, 'id' );

		$rem = $conn->get( self::SINGLE_INSTITUTION_ENDPOINT, new KlinkInstitutionDetails(), array('ID' => $id) );

		if( KlinkHelpers::is_error( $rem ) ){
			throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
		}

        if($this->telemeter!=null) $this->telemeter->afterOperation($conn->getUrl(),__FUNCTION__);

		return $rem;

	}

	/**
	 * Check if the institution exists
	 * @param string $idOrName the institution identifier or the name.
	 * @return boolean true if the institution exists, false otherwise 
	 * @throws IllegalArgumentException if the $idOrName is not a non-empty string
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function institutionExists( $idOrName ){

		KlinkHelpers::is_string_and_not_empty( $idOrName, 'idOrName' );

		$foundSomething = $this->getInstitutions( $idOrName );
		
		return !empty($foundSomething);

	}

	/**
	 * Get the name of the institution give it's identifier.
	 * 
	 * Retrieve the institution's name corresponding to the specified id. The response is not cached.
	 * 
	 * @param string $id the institution's identifier
	 * @return string|null the institution's name or null on error
	 */
	function getInstitutionName( $id )
	{
		try{

			$inst = $this->getInstitution( $id );

			return $inst->getName();

		} catch( Exception $e ) {

			return null;

		}

	}

	
	// ----- Static Utility Stuff

	/**
	 * Test the specified KlinkConfiguration for errors. 
	 * The test will verify also that the authentication parameter and the istitutionid are valid.
	 * 
	 * @param KlinkConfiguration $config the configuration to test
	 * @param Exception $error (in) the variable the will contain the detailed exception object
	 * @return  boolean true if the test passes, false otherwise
	 * */
	public static function test(KlinkConfiguration $config, &$error){

		try{

		  	$client = new KlinkCoreClient( $config );

		  	$res = null;

		  	try{

		  		$res = $client->getInstitutions();

		  	} catch(KlinkException $kei){

		  		// Expected
		  		// Method Not Allowed if invoking the version 1 of the K-Link Core api
		  		if( $config->isDebugEnabled() ){
		  			error_log( 'Exception message ' . $kei->getMessage() . PHP_EOL );
		  		}


		  		if( $kei->getMessage() != 'Method Not Allowed' ){

			  		if( $config->isDebugEnabled() ){

						error_log( '###### TEST EXCEPTION ###### ');
						error_log( print_r($res, true ) );
					
					}

				 	throw new Exception("Server not found or network problem.", 9, $kei);
				}

				//throw $keid;

		  	}

		  	try{

				$res = $client->getInstitution( $config->getInstitutionId() );

			} catch( KlinkException $keid ){

				if( $keid->getMessage() == 'Not Found' ){

			  		if( $config->isDebugEnabled() ){

						error_log( '###### TEST EXCEPTION ###### ');
						error_log( print_r($res, true ) );
					
					}

				 	throw new Exception("Institution details not found.", 10, $keid);

				}

				throw new Exception("Server not found or network problem.", 11, $keid);

			}

			if( $config->isDebugEnabled() ){

				error_log( '###### TEST RESPONSE ###### ');
				error_log( print_r($res, true ) );

			}

			$error = null;

		 	return true;

		} catch( KlinkException $ke ){

			if( $config->isDebugEnabled() ){

				error_log( '###### TEST EXCEPTION ###### ');
				error_log( print_r($res, true ) );
				
			}

		 	$error = $ke;

			return false;

		} catch( Exception $e ){
			if( $config->isDebugEnabled() ){

				error_log( '###### TEST EXCEPTION ###### ');
				error_log( print_r($res, true ) );
				
			}

		 	$error = $e;

			return false;
		}

	}

	/**
	 * Generate a PNG thumbnail of the given file.
	 * 
	 * @param string $fullFilePath the path of the file on disk where the reside the file to generate the thumbnail from.
	 * @param string $fullImagePath the path in which the thumbnail will be saved. The path must have the name of the file in it (extension is png).
	 * 
	 * @throws KlinkException if the service cannot generate the thumbnail or the file is in the wrong format
	 */
	public static function generateThumbnail( $fullFilePath, $fullImagePath, $resolution = 'small', $debug = false )
	{

		KlinkHelpers::is_string_and_not_empty( $fullFilePath, 'file path' );

		KlinkHelpers::is_string_and_not_empty( $fullImagePath, 'thumbnail image path' );


		if( !file_exists( $fullFilePath ) ){
			
			throw new KlinkException("File not exists");

		}

		$mime = KlinkDocumentUtils::get_mime( $fullFilePath );

		error_log( 'Generate thumbnail ' . $mime);


		if( !KlinkDocumentUtils::isMimeTypeSupported( $mime ) ){

			throw new KlinkException("Mimetype not supported");

		}


		$http = new KlinkHttp('http://localhost/');

		if( $debug ) {

			error_log( ' --> Generating thumbnail for ' . $fullFilePath );

		}

		$data = array(
			'filename' => basename( $fullFilePath ),
			'filemime' => KlinkDocumentUtils::get_mime($fullFilePath) ,
			'filedata' => base64_encode( file_get_contents( $fullFilePath ) )
			);

		$headers = array(
				'body' => json_encode($data),
				'timeout' => 120,
				'httpversion' => '1.1',
				'compress' => 'true', //we compress the data that is sended for bandwith management
				'headers' => array(
					'Content-Type' => 'application/json',
					'Authorization' => 'Basic ' . base64_encode( self::THUMBAIL_GENERATOR_AUTHENTICATION ) )
			);

		$result = $http->post( self::THUMBNAIL_GENERATOR_URL, $headers );

		if(KlinkHelpers::is_error($result)){

			if( $debug ){

				echo 'Error ' . PHP_EOL;
				error_log( print_r( $result, true ) );
				error_log( ' ERROR <--' );

			}

			throw new KlinkException("The thumbnail cannot be generated.");
		}
		else {

			$decoded = json_decode( $result['body'] );

			if( empty( $decoded->DataUri ) ){

				if( $debug ){

					echo 'Error ' . PHP_EOL;
					error_log( print_r( $decoded, true ) );
					error_log( ' ERROR <--' );

				}

				throw new KlinkException("The thumbnail cannot be generated. Empty image response.");
			}

			file_put_contents( $fullImagePath, file_get_contents( $decoded->DataUri ) );

		}

		return $fullImagePath;

	}

	/**
	 * Generate a document thumbnail from a KlinkDocument instance
	 * @param  KlinkDocument $document the document that needs the thumbnail
	 * @return string|boolean The image content in PNG format or false in case of error
	 * @throws InvalidArgumentException If the document data is empty or null
	 * @throws KlinkException If the mimetype is not compatible with the thumbnail generator or something bad happened
	 */
	public static function generateThumbnailFromDocument( KlinkDocument $document)
	{
		return self::generateThumbnailFromContent( $document->getDescriptor()->getMimeType(), base64_decode($document->getDocumentData()) );
	}


	/**
	 * Generate a document thumbnail from the content of a file
	 * @param  string  $mimeType      The mime type of the data that needs the thumbnail
	 * @param  string  $data          The document data used for the thumbnail generation
	 * @return string|boolean                 The image content in PNG format or false in case of error
	 * @internal
	 */
	public static function generateThumbnailFromContent( $mimeType, $data, $resolution = 'small', $debug = false )
	{

		KlinkHelpers::is_string_and_not_empty( $mimeType, 'mime type' );

		KlinkHelpers::is_string_and_not_empty( $data, 'data' );

		$fileExtension = KlinkDocumentUtils::getExtensionFromMimeType( $mimeType );


		if( !KlinkDocumentUtils::isMimeTypeSupported( $mimeType ) ){

			throw new KlinkException("Mimetype not supported");

		}


		$http = new KlinkHttp('http://localhost/');

		$data = array(
			'filename' => md5( KlinkHelpers::now() ) . '.' . $fileExtension,
			'filemime' => $mimeType ,
			'filedata' => base64_encode( $data )
			);

		$headers = array(
				'body' => json_encode($data),
				'timeout' => 120,
				'httpversion' => '1.1',
				'compress' => 'true', //we compress the data that is sended for bandwith management
				'headers' => array(
					'Content-Type' => 'application/json',
					'Authorization' => 'Basic ' . base64_encode( self::THUMBAIL_GENERATOR_AUTHENTICATION ) )
			);

		$result = $http->post( self::THUMBNAIL_GENERATOR_URL, $headers );

		if(KlinkHelpers::is_error($result)){

			if( $debug ){

				echo 'Error ' . PHP_EOL;
				error_log( print_r( $result, true ) );
				error_log( ' ERROR <--' );

			}

			throw new KlinkException("The thumbnail cannot be generated.");
		}
		else {

			$decoded = json_decode( $result['body'] );

			if( empty( $decoded->DataUri ) ){

				if( $debug ){

					echo 'Error ' . PHP_EOL;
					error_log( print_r( $decoded, true ) );
					error_log( ' ERROR <--' );

				}

				throw new KlinkException("The thumbnail cannot be generated. Empty image response.");
			}

			// file_put_contents( $fullImagePath, file_get_contents( $decoded->DataUri ) );

			return file_get_contents( $decoded->DataUri );

		}

		throw new KlinkException("The thumbnail cannot be generated. Unexpected end of function.");

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