<?php

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

if( !defined( 'KLINK_BOILERPLATE_VERSION' ) ){
	define( 'KLINK_BOILERPLATE_VERSION', '2.1.0' );
}

/**
*  KlinkCoreClient.
*  offers a clean API to perform actions on the Klink Core
*/
final class KlinkCoreClient
{
	
	use LoggerAwareTrait;

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
	const THUMBNAIL_GENERATOR_URL = 'thumbnails/';


	const HEALTH_FAST_CHECK_ENDPOINT = 'monitor/health/http_status_checks';
	
	const HEALTH_CHECK_ENDPOINT = 'monitor/health/run';

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
	
	

	/**
	 * Creates a KlinkCoreClient
	 *
	 * @return KlinkCoreClient
	 */
	function __construct( KlinkConfiguration $config, LoggerInterface $logger = null, IKlinkCoreTelemeter $telemeter=null )
	{

		$this->telemeter= $telemeter;

        $this->configuration = $config;
		
		$this->logger = $logger;

		foreach ($this->configuration->getCores() as $core) {

			$this->rest[$core->getTag()] = new KlinkRestClient($core->getCore(), $core, array('debug' => $this->configuration->isDebugEnabled()), $logger);

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

		$conn = self::_get_connection($document->getDescriptor()->getVisibility());

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);


		$array = array(
			'descriptor' => $document->getDescriptor(),
			'documentData' => $document->getDocumentBase64Stream(),
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

		$conn = self::_get_connection($document->getVisibility());

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);
		
		

		$rem = $conn->delete( $this->_parameter_substitution(self::SINGLE_DOCUMENT_ENDPOINT, 
			array(
				'VISIBILITY' => $document->getVisibility(),
				'INSTITUTION_ID' => $document->getInstitutionID(),
				'LOCAL_DOC_ID' => $document->getLocalDocumentID(),
				)));

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

		if(is_null($visibility)){
			$visibility = KlinkVisibilityType::KLINK_PUBLIC;
		}
		else {
			$visibility = KlinkVisibilityType::fromString($visibility);
		}

		$conn = self::_get_connection($visibility);

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);


		$rem = $conn->delete( $this->_parameter_substitution(self::SINGLE_DOCUMENT_ENDPOINT, 
			array(
				'VISIBILITY' => $visibility,
				'INSTITUTION_ID' => $institution,
				'LOCAL_DOC_ID' => $document,
				) 
		));

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

		if(is_null($visibility)){
			$visibility = KlinkVisibilityType::KLINK_PUBLIC;
		}
		else {
			$visibility = KlinkVisibilityType::fromString($visibility);
		}
		
		$conn = self::_get_connection($visibility);

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);

		KlinkHelpers::is_valid_id( $institutionId, 'institution id' );
		KlinkHelpers::is_valid_id( $documentId, 'local document id' );




		$rem = $conn->get( $this->_parameter_substitution(self::SINGLE_DOCUMENT_ENDPOINT, 
			array(
			'VISIBILITY' => $visibility,
			'INSTITUTION_ID' => $institutionId,
			'LOCAL_DOC_ID' => $documentId)), new KlinkDocumentDescriptor());

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
	 * @param KlinkFacet[] $facets The facets that needs to be retrieved or what will be retrieved. Default null, no facets will be calculated or filtered.
	 * @return KlinkSearchResult returns the document that match the searched terms
	 * @throws KlinkException if something wrong happened during the communication with the core
	 */
	function search( $terms, $type = null, $resultsPerPage = 10, $offset = 0, $facets = null ){

		if(is_null($type)){
			$type = \KlinkVisibilityType::KLINK_PUBLIC;
		}

		if(!empty($facets)){
			KlinkHelpers::is_array_of_type($facets, 'KlinkFacet', 'facets');
		}


		$conn = self::_get_connection($type);

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);


        $search_params = array_merge(array(
				'query' => $terms,
				'numResults' => $resultsPerPage,
				'startResult' => $offset
			), $this->_collapse_facets($facets));
			
		// so, the name `query` is a Guzzle reserved term, so we need to wrap things otherwise the request will fail

		$rem = $conn->get( $this->_parameter_substitution(self::SEARCH_ENDPOINT, ['VISIBILITY' => $type]), new KlinkSearchResult(), ['query' => $search_params] );

		if(KlinkHelpers::is_error($rem)){
			throw new KlinkException((string)$rem, $rem->get_error_data_code());
		}

        if($this->telemeter!=null) $this->telemeter->afterOperation($conn->getUrl(),__FUNCTION__);

		return $rem;
	}

	/**
	 * Retrieve only the specified facets from the available documents that has the specified visibility
	 *
	 * to construct the facets parameter @see KlinkFacetsBuilder
	 *
	 * @param KlinkFacet[]|string[] $facets The facets to be retrieved. You can pass also an array of string with the facet names, the default configuration will be applyied
	 * @param string $visibility The visibility 
	 * @return [type] [description]
	 */
	public function facets( $facets, $visibility = 'public', $term = '*' )
	{

		if(!is_null($facets)){
			KlinkHelpers::is_array_of_type($facets, 'KlinkFacet', 'facets');
		}

		KlinkHelpers::is_string_and_not_empty($term, 'term');

		if(empty($facets)){
			throw new IllegalArgumentException("You have to specify at least one facet", 2);
		}

		$conn = self::_get_connection($visibility);

		$params = array_merge(array(
				'query' => $term,
				'numResults' => 0,
				'startResult' => 0
			), $this->_collapse_facets($facets));
			
		// so, the name `query` is a Guzzle reserved term, so we need to wrap things otherwise the request will fail

		$rem = $conn->get( $this->_parameter_substitution(self::SEARCH_ENDPOINT, ['VISIBILITY' => $visibility]), new KlinkSearchResult(), ['query' => $params] );


		if(KlinkHelpers::is_error($rem)){
			throw new KlinkException((string)$rem, $rem->get_error_data_code());
		}

		return $rem->getFacets();
	}


	// ----- Suggestions

	// function autocomplete( $terms, $type = null ){

	// 	if(is_null($type)){
	// 		$type = KlinkSearchType::KLINK_PUBLIC;
	// 	}

	// 	$conn = self::_get_connection();

	// 	return $conn->getCollection(self::AUTOCOMPLETE_ENDPOINT, 
	// 		array(
	// 			'query' => $terms,
	// 			'visibility' => $type
	// 		), 'string');
	// }

	/**
	 * Delete an institution from the K-Link Core
	 * 
	 * @param  string $id The institution identifier
	 * @return boolean     true in case the institution has been succesfully removed
	 * @throws KlinkException If the K-Link Core returned an error
	 */
	public function deleteInstitution( $id )
	{

		$conn = self::_get_connection(\KlinkVisibilityType::KLINK_PUBLIC);

		$rem = $conn->delete( $this->_parameter_substitution(self::SINGLE_INSTITUTION_ENDPOINT, 
			array(
				'ID' => $id,
				) 
		));

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
		
		$conn = self::_get_connection(\KlinkVisibilityType::KLINK_PUBLIC);

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

		$conn = self::_get_connection(\KlinkVisibilityType::KLINK_PUBLIC);

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

		$conn = self::_get_connection(\KlinkVisibilityType::KLINK_PUBLIC);

        if($this->telemeter!=null) $this->telemeter->beforeOperation($conn->getUrl(),__FUNCTION__);

		KlinkHelpers::is_valid_id( $id, 'id' );

		$rem = $conn->get( $this->_parameter_substitution(self::SINGLE_INSTITUTION_ENDPOINT, array('ID' => $id)), new KlinkInstitutionDetails() );

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


	// ----- Document statistics


	/**
	 * Returns the number of public documents that the specified institution has
	 * 
	 * If the institution is not specified the configured institution for the connection will be assumed.
	 * 
	 * @param string $institution (optional) the institution ID
	 * @return the number of public document, in case of error false is returned
	 * @throws InvalidArgumentException if the specified institution is not a valid institution identifier
	 */
	public function getPublicDocumentsCount( $institution = null )
	{

		if(is_null($institution)){
			$institution = $this->configuration->getInstitutionId();
		}

		KlinkHelpers::is_valid_id($institution, 'institution');

		try{

			$search = $this->search("*", KlinkSearchType::KLINK_PUBLIC, 0, 0, KlinkFacetsBuilder::create()->institution($institution)->build());

			return $search->getTotalResults();

		}catch(KlinkException $ke){
			return false;
		}
	}

	/**
	 * Returns the number of private documents that are owned by the institution. 
	 * 
	 * If the institution is not specified the configured institution for the connection will be assumed.
	 * 
	 * Remember that institution's private documents are only accessible if directly connected to the institution's core with 
	 * a user that has the right permissions.
	 * 
	 * @param string $institution (optional) the institution ID
	 * @return the number of private document, in case of error (or the operation is forbidden) false is returned
	 * @throws InvalidArgumentException if the specified institution is not a valid institution identifier
	 */
	public function getPrivateDocumentsCount($institution = null)
	{

		if(is_null($institution)){
			$institution = $this->configuration->getInstitutionId();
		}

		KlinkHelpers::is_valid_id($institution, 'institution');

		try{

			$search = $this->search("*", KlinkSearchType::KLINK_PRIVATE, 0, 0, KlinkFacetsBuilder::create()->institution($institution)->build());

			return $search->getTotalResults();

		}catch(KlinkException $ke){
			return false;
		}
	}


	
	// ----- Static Utility Stuff

	/**
	 * Test the specified KlinkConfiguration for errors. 
	 * The test will verify also that the authentication parameter and the istitutionid are valid.
	 * 
	 * @param KlinkConfiguration $config the configuration to test
	 * @param Exception $error (in) the variable the will contain the detailed exception object
	 * @param boolean $health_info (in) pass a variable here to gather health details
	 * @return  boolean true if the test passes, false otherwise. 
	 * */
	public static function test(KlinkConfiguration $config, &$error, $perform_health_check = false, &$health_info=null, LoggerInterface $logger = null){

		$client = null;
        
        if(is_null($logger)){
            $logger = new NullLogger();
        }
        
        $logger->warning('Starting configuration test', array('config' => $config));

		try{

		  	$client = new KlinkCoreClient( $config );
            
            $cores = $config->getCores();
            $cores_count = count($cores);
            
            if($cores_count == 0 || $cores_count > 1){
                throw new Exception('The configuration test can only be performed on a configuration with only one Core', 10000);
            }
            
            $is_private = $cores[0]->getTag() === KlinkVisibilityType::KLINK_PRIVATE;

		  	$res = null;
		
		  	try{
                  
                 $search = $client->search("*", $is_private ? KlinkVisibilityType::KLINK_PRIVATE : KlinkVisibilityType::KLINK_PUBLIC, 0, 0);

			     $res = $search->getTotalResults();

		  	} catch(KlinkException $kei){

		  		if( $kei->getCode() == 401 ){

			  		if( $config->isDebugEnabled() ){

						$logger->debug( 'Test - search capability - 401 error ' . $kei->getMessage(), compact('is_private', 'kei', 'res') );
					
					}

				 	throw new KlinkException("Wrong username or password.", $kei->getCode(), $kei);
				}

				else if( $kei->getCode() == 403 ){

			  		if( $config->isDebugEnabled() ){

						$logger->debug( 'Test - search capability - 403 error ' . $kei->getMessage(), compact('is_private', 'kei', 'res') );
					
					}

				 	throw new KlinkException("Unauthorized to perform search in Private document set. Please review your username and password.", $kei->getCode(), $kei);
				}

				else {

			  		if( $config->isDebugEnabled() ){
  
		  			    $logger->debug( 'Test - search capability ' . $kei->getMessage(), compact('is_private', 'kei', 'res') );
					
					}

				 	throw new KlinkException("Server not found or network problem.", $kei->getCode(), $kei);
				}

		  	}

			$error = null;
			
			if($perform_health_check){
				$health_info = $client->health();
			}

		 	return true;

		} catch( KlinkException $ke ){

			if( $config->isDebugEnabled() ){

				$logger->debug( 'Test - CoreClient instance construction caused KlinkException ' . $ke->getMessage(), compact('ke') );
				
			}
            
            if ($logger) {
                $logger->error('Configuration test KlinkException', array('error' => $ke));
            }

		 	$error = $ke;
			 
			if(!is_null($client) && $perform_health_check){
				$health_info = $client->health();
			}

			return false;

		} catch( Exception $e ){
			if( $config->isDebugEnabled() ){

				$logger->debug( 'Test - CoreClient instance construction caused Exception ' . $ke->getMessage(), ['error' => $e, 'res' => isset($res) ? $res : null] );
				
			}
            
            if ($logger) {
                $logger->error('Configuration test Exception', array('error' => $e));
            }

		 	$error = $e;
			
			if(!is_null($client) && $perform_health_check){
				$health_info = $client->health();
			}

			return false;
		}

	}
	
	
	/**
		Get the health information of the currently connected/configured K-Link Core
	
		@param boolean $fast if true perform a rapid OK or Failure test
		
		@return boolean|KlinkHealthResults return true or false when the fast health check is performed, otherwise an instance of KlinkHealthResults 
	*/
	public function health($fast = false){
		// fast => 200 OK or 502 Bad Gateway response
		// in case of 404 nor found during fast check return true (maybe is an old core)
		
		if($fast){
		
			try{
		
				$conn = self::_get_connection();
		
		
				$rem = $conn->get( self::HEALTH_FAST_CHECK_ENDPOINT, new KlinkHealthResults() );
		
				if( KlinkHelpers::is_error( $rem ) ){
					throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
				}		        
		
				return true;
			
			}catch(KlinkException $ex){
				
				if($ex->getCode()===404){
					return true;
				}
				
				return false;
			}
		
		}
		
		
		try{
		
			$conn = self::_get_connection();
	
			$rem = $conn->get( self::HEALTH_CHECK_ENDPOINT, new KlinkHealthResults() );
	
			if( KlinkHelpers::is_error( $rem ) ){
				throw new KlinkException( (string)$rem, $rem->get_error_data_code() );
			}		        
	
			return $rem;
		
		}catch(KlinkException $ex){
			
			if($ex->getCode()===404){
				return null;
			}
			
			return null;
		}catch(Exception $ex){
			
			return null;
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
	public function generateThumbnail( $fullFilePath, $fullImagePath, $resolution = 'default', $debug = false )
	{

		KlinkHelpers::is_string_and_not_empty( $fullFilePath, 'file path' );

		KlinkHelpers::is_string_and_not_empty( $fullImagePath, 'thumbnail image path' );


		if( !file_exists( $fullFilePath ) ){
			
			throw new KlinkException("File not exists");

		}

		$mime = KlinkDocumentUtils::get_mime( $fullFilePath );

		if( $debug ){
			error_log( 'Generate thumbnail ' . $fullFilePath . '[' . $mime . ']' );
		}


		if( !KlinkDocumentUtils::isMimeTypeSupported( $mime ) ){

			throw new KlinkException("Mimetype not supported");

		}


		$doc_type = KlinkDocumentUtils::documentTypeFromMimeType( $mime );


		if( $doc_type === 'image' && !defined('KLINK_COMPATIBILITY_MODE') && defined('IMAGETYPE_PNG') ){
			//we already have an image so let's resize it
			
			try{
			
				$image = new KlinkImageResize($fullFilePath);
				$image->resizeToWidth(300);
				$image->save($fullImagePath, IMAGETYPE_PNG);

			}
			catch(Exception $ie){

				if( $debug ) {
					error_log( ' --> Thumbnail from image cannot be generated ' . $ie->getMessage() );
				}


				throw new KlinkException("The thumbnail cannot be generated. ($ie->getMessage())");
			}

			return $fullImagePath;
		}


		if( $debug ) {

			error_log( ' --> Generating thumbnail for ' . $fullFilePath );

		}

		$data = array(
			'fileName' => basename( $fullFilePath ),
			'fileMime' => KlinkDocumentUtils::get_mime($fullFilePath) ,
			'fileData' => base64_encode( file_get_contents( $fullFilePath ) )
			);

		$conn = self::_get_connection();

		$rem = $conn->post( self::THUMBNAIL_GENERATOR_URL, $data, new KlinkThumbnail() );

		if(KlinkHelpers::is_error($rem)){

			if( $debug ){

				echo 'Error ' . PHP_EOL;
				error_log( print_r( $rem, true ) );
				error_log( ' ERROR <--' );

			}

			throw new KlinkException((string)$rem, $rem->get_error_data_code());
		}
		else {

			if( empty( $rem->dataURI ) ){

				if( $debug ){

					echo 'Error ' . PHP_EOL;
					error_log( print_r( $rem, true ) );
					error_log( ' ERROR <--' );

				}

				throw new KlinkException("The thumbnail cannot be generated. Empty image response.");
			}

			file_put_contents( $fullImagePath, file_get_contents( $rem->dataURI ) );

		}

		return $fullImagePath;

	}

	/**
	 * Generate a document thumbnail from a KlinkDocument instance.
	 *
	 * This method does not supports Image documents, please use @see generateThumbnail
	 * 
	 * @param  KlinkDocument $document the document that needs the thumbnail
	 * @return string|boolean The image content in PNG format or false in case of error
	 * @throws InvalidArgumentException If the document data is empty or null
	 * @throws KlinkException If the mimetype is not compatible with the thumbnail generator or something bad happened
	 */
	public function generateThumbnailFromDocument( KlinkDocument $document)
	{
		$doc_stream = $document->getDocumentStream();
		
		$thumb_return = self::generateThumbnailFromContent( $document->getDescriptor()->getMimeType(), $doc_stream );
		
		if( @get_resource_type($doc_stream) !== 'Unknown' ){
			fclose($doc_stream);
		}
		
		return $thumb_return;
	}


	/**
	 * Generate a document thumbnail from the content of a file.
	 *
	 * The file content MUST NOT be encoded in base64 format
	 * 
	 * @param  string  $mimeType      The mime type of the data that needs the thumbnail
	 * @param  string|resource  $data The document data used for the thumbnail generation
	 * @return string|boolean         The image content in PNG format or false in case of error
	 * @internal
	 */
	public function generateThumbnailFromContent( $mimeType, $data, $resolution = 'small', $debug = false )
	{

		KlinkHelpers::is_string_and_not_empty( $mimeType, 'mime type' );

		$fileExtension = KlinkDocumentUtils::getExtensionFromMimeType( $mimeType );


		if( !KlinkDocumentUtils::isMimeTypeSupported( $mimeType ) ){

			throw new KlinkException("Mimetype not supported");

		}

		$doc_type = KlinkDocumentUtils::documentTypeFromMimeType( $mimeType );

		if( $doc_type === 'image' && !defined('KLINK_COMPATIBILITY_MODE') && defined('IMAGETYPE_PNG') ){
			//we already have an image so let's resize it
			
			try{
			
				$image = new KlinkImageResize();
				
				if(is_string($data) && @is_file($data)){
					$image->load($data);
				}
				else if(is_string($data)){
					$image->loadFromString($data);
				}
				else if( @is_resource($value) && @get_resource_type($value) === 'stream' ){
					// TODO: this can potentially use a big chunk of RAM
					$image->loadFromString(stream_get_contents($data));
				}
				else if( @get_resource_type($value) === 'Unknown' ){
					throw new UnexpectedValueException('The original data stream is closed');
				}
				
				
				$image->resizeToWidth(300);
				return $image->get(IMAGETYPE_PNG);

			}
			catch(Exception $ie){

				if( $debug ) {
					error_log( ' --> Thumbnail from image cannot be generated ' . $ie->getMessage() );
				}


				throw new KlinkException('The thumbnail cannot be generated: ' . $ie->getMessage());
			}

			return false;
		}

		$data = array(
			'fileName' => md5( KlinkHelpers::now() ) . '.' . $fileExtension,
			'fileMime' => $mimeType ,
			'fileData' => \KlinkDocumentUtils::getBase64Stream( $data )
			);

		$conn = self::_get_connection();

		$rem = $conn->post( self::THUMBNAIL_GENERATOR_URL, $data, new KlinkThumbnail() );

		if(KlinkHelpers::is_error($rem)){
			throw new KlinkException((string)$rem, $rem->get_error_data_code());
		}

		if( empty( $rem->dataURI ) ){

			if( $debug ){

				error_log( 'Error ' . PHP_EOL);
				error_log( print_r( $rem, true ) );
				error_log( ' ERROR <--' );

			}

			throw new KlinkException("The thumbnail has not been generated. Empty image response.");
		}

		return file_get_contents( $rem->dataURI );


		throw new KlinkException("The thumbnail cannot be generated. Unexpected end of function.");

	}

	/**
	 * Generate a thumbnail of the given URL. Only web pages are supported.
	 *
	 * @param string $url the url of the page for the screenshot
	 * @param string $image_file If specified is the path in which the file will be saved. Put null if you want the data back as the function return (default: null)
	 * @return string|int|boolean The image content in PNG format if $image_file is null, the return of file_put_contents if a file path is specified
	 * @throws InvalidArgumentException If the specified URL is not well formed
	 * @throws KlinkException If the mimetype is not compatible with the thumbnail generator or the thumbnail cannot be generated
	 */
	public function generateThumbnailOfWebSite($url, $image_file = null)
	{

		KlinkHelpers::is_valid_url( $url, 'url' );

		if(!is_null($image_file)){
			KlinkHelpers::is_string_and_not_empty( $image_file, 'thumbnail image path cannot be empty' );
		}

		$url_length = strlen($url);

		// remember to remove the last slash from the url otherwise the Core will get mad
		if($url[$url_length-1] == '/'){

			$url[$url_length-1] = ' ';

			$url = trim($url);
		}

		$data = self::generateThumbnailFromContent( 'text/uri-list', $url);

		if(!is_null($image_file)){
			return file_put_contents( $fullImagePath, file_get_contents( $decoded->dataURI ) );
		}

		return $data;
	}

	/**
	 * The Boilerplate version.
	 *
	 * @return string The boilerplate version number.
	 */
	public static function version(){
		return defined('KLINK_BOILERPLATE_VERSION') ? KLINK_BOILERPLATE_VERSION : '0.3.33';
	}

	// ----- Private Stuff


	/**
	 * Get the connection to the Klink Core for performing the request.
	 * The connection is selected considering the tag name. Current tag names are based on the public/private visibility.
	 * For compatibility reason if only one Core connection is configured and the required tag cannot be found, the only configured connection will be returned
	 * 
	 * @return KlinkRestClient the KlinkRestClient to use
	 * @throws KlinkCoreSelectionException if a core with the specified tag is not found 
	 */
	private function _get_connection($tag = \KlinkVisibilityType::KLINK_PRIVATE){

		if(array_key_exists($tag, $this->rest)){
			return $this->rest[$tag];
		}
		
		if(count($this->rest) === 1){
			$vals = array_values($this->rest);
			return $vals[0];
		}
		
		throw new \KlinkCoreSelectionException($tag, array_keys($this->rest));

	}


	/**
	 * 
	 */
	private function _collapse_facets($facet_array)
	{
		$arr = array();
		$return = array();
		$fs = array();

		if(empty($facet_array)){
			return $return;
		}
		
		foreach ($facet_array as $facet) {
			$arr = $facet->toKlinkParameter();

			if(isset($arr['facets'])){
				$fs[] = $arr['facets'];
				unset($arr['facets']);
				$return = array_merge($return, $arr);
			}
			else {
				$return = array_merge($return, $arr);	
			}
		}

		$return['facets'] = implode(',', $fs);

		return $return;
	}
	
	
	private function _parameter_substitution($string, array $params = null){
		
		KlinkHelpers::is_string_and_not_empty( $string, 'string' );


		if( !is_null( $params ) && !empty( $params ) ){

			foreach ( $params as $key => $value ) {
				
				if( strpos( $string, "{$key}" ) !== false ){

					$string = str_replace('{' . $key . '}', $value, $string);

				}

			}
			
		}

		return $string;
	
	}
}
