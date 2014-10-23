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
	 */

	public $id;

	/**
	 * getId
	 * @return string
	 */
	public function getId() {
		return $this->id;
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
	}
	/**
	 * getAbstract
	 * @return string
	 */
	public function getAbstract() {
		return $this->abstract;
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

	/**
	 * authors
	 * @var KlinkDocumentAuthor[]
	 */

	public $authors;

	/**
	 * setAuthors
	 * @param string[] $value
	 * @return void
	 */
	public function setAuthors(array $value) {
		$this->authors = $value;
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

	/**
	 * setType
	 * @param $value
	 * @return void
	 */
	public function setMimeType($value) {
		$this->mimeType = $value;
	}
	/**
	 * getType
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * institution
	 * @var KlinkInstitutionDetails
	 */

	public $institutionID;

	/**
	 * Owner institution
	 * @return KlinkInstitutionDetails
	 */
	public function getInstitutionID() {
		return $this->institutionID;
	}

	/**
	 * creationDate
	 * @var Date
	 */

	public $creationDate;

	/**
	 * setCreationDate
	 * @param Date $value
	 * @return void
	 */
	public function setCreationDate(Date $value) {
		$this->creationDate = $value;
	}
	/**
	 * getCreationDate
	 * @return Date
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
	 */

	public $visibility;

	/**
	 * setVisibility
	 * @param KlinkVisibilityType $value
	 * @return void
	 */
	public function setVisibility(KlinkVisibilityType $value) {
		$this->visibility = $value;
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

	/**
	 * setDocumentType
	 * @param $value
	 * @return void
	 */
	public function setDocumentType($value) {
		$this->documentType = $value;
	}
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

	/**
	 * setUserUploader
	 * @param $value
	 * @return void
	 */
	public function setUserUploader($value) {
		$this->userUploader = $value;
	}
	/**
	 * getUserUploader
	 * @return string
	 */
	public function getUserUploader() {
		return $this->userUploader;
	}

	/**
	 * localDocumentID
	 * @var string
	 */

	public $localDocumentID;

	/**
	 * setLocalDocumentID
	 * @param $value
	 * @return void
	 */
	public function setLocalDocumentID($value) {
		$this->localDocumentID = $value;
	}
	/**
	 * getLocalDocumentID
	 * @return string
	 */
	public function getLocalDocumentID() {
		return $this->localDocumentID;
	}


	function __construct($id = '', $institutionID = '', $localDocumentID = '', $hash = ''){
		$this->id = $id;
		$this->institutionID = $institutionID;
		$this->localDocumentID = $localDocumentID;
		$this->hash = $hash;
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