<?php


/**
* Define the basic information that describe a document. The official documentation call this a Document Descriptor.
* @package Klink
* @since 0.1.0
*/
final class KlinkDocumentDescriptor
{

	/**
	 * id
	 * @var string
	 * @internal this property might be forever null
	 */

	// public $id;

	/**
	 * getId
	 * @return string
	 * @internal this parameter might be forever null
	 */
	// public function getId() {
	// 	return $this->id;
	// }
	
	
	/**
	 *
	 * @param $institutionID string The Institution ID
	 * @param $localDocumentID string The LocalDocument ID
	 *
	 */
	public static buildKlinkId($institutionID, $localDocumentID) {
	    KlinkHelpers::is_valid_id( $institutionID, 'institution id' );
        KlinkHelpers::is_valid_id( $localDocumentID, 'local document id' );	  
	    return $institutionID . '-' . $localDocumentID;
	}


	public function getKlinkId() {
        return KlinkDocumentDescriptor::buildKlinkId($this->institutionID, $this->localDocumentID);
	}


	/**
	 * institution
	 * @var string
	 */

	public $institutionID;

	/**
	 * Owner institution
	 * @return string
	 */
	public function getInstitutionID() {
		return $this->institutionID;
	}



	/**
	 * localDocumentID
	 * @var string
	 */

	public $localDocumentID;

	// /**
	//  * setLocalDocumentID
	//  * @param $value
	//  * @return void
	//  */
	// public function setLocalDocumentID($value) {
	// 	$this->localDocumentID = $value;
	// }
	/**
	 * getLocalDocumentID
	 * @return string
	 */
	public function getLocalDocumentID() {
		return $this->localDocumentID;
	}



	/**
	 * hash
	 * @var string
	 */

	public $hash;

	/**
	 * getHash
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * title
	 * @var string
	 */

	public $title;

	/**
	 * setTitle
	 * @param $value
	 * @return void
	 */
	public function setTitle($value) {
		$this->title = $value;
	}
	/**
	 * getTitle
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * uri
	 * @var string
	 */

	public $documentURI;

	/**
	 * getUri
	 * @return string
	 */
	public function getDocumentUri() {
		return $this->documentURI;
	}

	/**
	 * abstract
	 * @var string
	 */

	public $abstract;

	/**
	 * setAbstract
	 * @param $value
	 * @return void
	 */
	public function setAbstract($value) {
		$this->abstract = $value;

		return $this;
	}
	/**
	 * getAbstract
	 * @return string
	 */
	public function getAbstract() {
		return $this->abstract;
	}

	

	/**
	 * authors
	 * @var array
	 */

	public $authors;

	/**
	 * setAuthors
	 * @param string[] $value
	 * @return void
	 */
	public function setAuthors(array $value) {
		$this->authors = $value;

		return $this;
	}
	/**
	 * getAuthors
	 * @return string[]
	 */
	public function getAuthors() {
		return $this->authors;
	}

	/**
	 * type
	 * @var string
	 */

	public $mimeType;

	// /**
	//  * setType
	//  * @param $value
	//  * @return void
	//  */
	// public function setMimeType($value) {
	// 	$this->mimeType = $value;
	// }
	/**
	 * getType
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	

	/**
	 * creationDate
	 * @var string
	 */

	public $creationDate;

	// /**
	//  * setCreationDate
	//  * @param Date $value
	//  * @return void
	//  */
	// public function setCreationDate(Date $value) {
	// 	$this->creationDate = $value;
	// }
	/**
	 * getCreationDate
	 * @return string
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}


	/**
	 * language
	 * @var string
	 */

	public $language;

	/**
	 * setLanguage
	 * @param $value
	 * @return void
	 */
	public function setLanguage($value) {
		$this->language = $value;

		return $this;
	}
	/**
	 * getLanguage
	 * @return string
	 */
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * thumbnailURI
	 * @var string
	 */

	public $thumbnailURI;

	/**
	 * setThumbnailURI
	 * @param $value
	 * @return void
	 */
	public function setThumbnailURI($value) {
		$this->thumbnailURI = $value;

		return $this;
	}
	/**
	 * getThumbnailURI
	 * @return string
	 */
	public function getThumbnailURI() {
		return $this->thumbnailURI;
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

		return $this;
	}

	/**
	 * getVisibility
	 * @return KlinkVisibilityType
	 */
	public function getVisibility() {
		return $this->visibility;
	}

	/**
	 * documentType
	 * @var string
	 */

	public $documentType;

	// /**
	//  * setDocumentType
	//  * @param $value
	//  * @return void
	//  */
	// public function setDocumentType($value) {
	// 	$this->documentType = $value;
	// }
	/**
	 * getDocumentType
	 * @return string
	 */
	public function getDocumentType() {
		return $this->documentType;
	}

	/**
	 * userUploader
	 * @var string
	 */

	public $userUploader;

	// /**
	//  * setUserUploader
	//  * @param $value
	//  * @return void
	//  */
	// public function setUserUploader($value) {
	// 	$this->userUploader = $value;
	// }
	/**
	 * getUserUploader
	 * @return string
	 */
	public function getUserUploader() {
		return $this->userUploader;
	}

	/**
	 * Reference person.
	 * 
	 * The person to contact for getting details
	 * @var string
	 */

	public $userOwner;

	/**
	 * getReferencePerson
	 * @return string
	 */
	public function getUserOwner() {
		return $this->userOwner;
	}

	


	function __construct(){

	}


	public static function create($institutionID, $localDocumentID, $hash, $title, $mimetype, $documentURI, $thumbnailURI, $userUploader, $userOwner, $visibility = null, $creationDate = null){

        KlinkHelpers::is_valid_id( $institutionID, 'institution id' );

        KlinkHelpers::is_valid_id( $localDocumentID, 'local document id' );

        KlinkHelpers::is_string_and_not_empty( $hash, 'hash' );

        KlinkHelpers::is_string_and_not_empty( $mimetype, 'mime type' );

        if( !is_null( $creationDate ) ){
            KlinkHelpers::is_valid_date_string( $creationDate, 'creation date' );
        }
        else {
            $creationDate = KlinkHelpers::now();
        }

        if(is_null($visibility)){
			$visibility = KlinkVisibilityType::KLINK_PUBLIC;
		}
        


        $instance = new self();

        //$instance->id = $institutionID . '-' . $localDocumentID;

        $instance->institutionID = $institutionID;
		
		$instance->localDocumentID = $localDocumentID;
		
		$instance->hash = $hash;

        $instance->title = $title;

        $instance->creationDate = $creationDate;

        $instance->thumbnailURI = $thumbnailURI;

        $instance->documentURI = $documentURI;

        $instance->mimeType = $mimetype;

        $instance->documentType = KlinkDocumentUtils::documentTypeFromMimeType( $mimetype );

        $instance->userUploader = $userUploader;

        $instance->userOwner = $userOwner;

        $instance->visibility = $visibility;

        return $instance;

    }

	/**
	 * For JSON serialization purporses
	 * */
	public function to_array(){
		$json = array();
	    foreach($this as $key => $value) {
	    	if(is_array($value)){
	    		$json[$key] = "array";
	    	}
	    	else {
		        $json[$key] = $value;
		    }
	    }
	    return $json; // or json_encode($json)
	}


}