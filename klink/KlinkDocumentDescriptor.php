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
	public static function buildKlinkId($institutionID, $localDocumentID) {
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
	 * @throws InvalidArgumentException If the given url is not syntactically valid
	 */
	public function setThumbnailURI($value) {

		KlinkHelpers::is_valid_url($value);

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


	// --- Core v2.1 fields
	
	/**
	 * List of Location String(s), if  this field is left empty then this value will be set during the indexing process
	 * 
	 * @var string[]
	 */
	public $locationsString;

	/**
	 * List of @see KlinkLocationDescriptor that the document talks about
	 * 
	 * @var KlinkLocationDescriptor[]
	 */
	public $locations;

	/**
	 * List of topics contained in the document
	 * 
	 * @var string[]
	 */
	public $topicTerms;

	/**
	 * The list of folders where the document can be found. Every folder is the  “document storage” relative path of the file. The path separator is “/” (slash)
	 * 
	 * @var string[]
	 */
	public $documentFolders;

	/**
	 * The list of groups assigned to the document. Each string in the list is in the form “user_id:group_id”
	 * where: group_id is the identifier of the group, and the user_id is the user identifier of the user owning the group (the _ids are internally defined by the DMS)
	 *
	 *
	 * @internal Please do not edit this field directly, use @see addDocumentGroup, @see removeDocumentGroup or @see setDocumentGroups 
	 * @var string[]
	 */
	public $documentGroups;

	/**
	 * The (overrided) titles of the document, as defined by users when inserting the document in a group
	 * 
	 * @var string[]
	 */
	public $titleAliases;


	/**
	 * setDocumentGroups
	 * @param string[] $value the new values for the document groups, each element in the array must be formatted as user_id:group_id, where user_id adn group_id are integers and user_id could be 0
	 * @return void
	 * @throws InvalidArgumentException If the @see $value array is not well formed
	 */
	public function setDocumentGroups($value) {
		$this->documentGroups = $value;
	}
	/**
	 * getDocumentGroups
	 * @return string[]
	 */
	public function getDocumentGroups() {
		return $this->documentGroups;
	}

	/**
	 * Add a document groups if not exists
	 * @param integer $user_id  The identifier of the user owner of the group (use 0 if is an institution group)
	 * @param integer $group_id The identifier of the group
	 * @return KlinkDocumentDescriptor
	 * @throws InvalidArgumentException If @see $user_id or @group_id are negative numbers
	 */
	public function addDocumentGroup($user_id, $group_id)
	{

		$composed_id = $user_id.':'.$group_id;

		KlinkHelpers::is_valid_document_group($composed_id);

		if(empty($this->documentGroups)){
			$this->documentGroups = array($composed_id);
		}
		else if(!in_array($composed_id, $this->documentGroups)){
			$this->documentGroups[] = $composed_id;
		}
		
		return $this;
	}

	/**
	 * Removes a group from the document groups list
	 *
	 * If the requested group does not exists the document groups field will not be touched and no error will be raised
	 * 
	 * @param  [type] $user_id  The identifier of the user owner of the group (use 0 if is an institution group)
	 * @param  [type] $group_id The identifier of the group
	 * @return KlinkDocumentDescriptor
	 * @throws InvalidArgumentException If @see $user_id or @group_id are negative numbers
	 */
	public function removeDocumentGroup($user_id, $group_id)
	{
		
		$composed_id = $user_id.':'.$group_id;

		KlinkHelpers::is_valid_document_group($composed_id);

		if(!empty($this->documentGroups) && in_array($composed_id, $this->documentGroups)){
			// let's remove the group
			$this->documentGroups = array_values(array_diff($this->documentGroups, array($composed_id)));
		}


		return $this;
	}



	/**
	 * Adds a new title alias for the document (if not already exists)
	 * @param string $title the title to add
	 * @return KlinkDocumentDescriptor
	 */
	public function addTitleAlias($title)
	{
		if(empty($this->titleAliases) && !empty($title)){
			$this->titleAliases = array($title);
		}
		else if(!empty($title) && !empty($this->titleAliases) && !in_array($title, $this->titleAliases)) {
			$this->titleAliases[] = $title;
		}

		return $this;
	}

	/**
	 * Removes a title, if exists, from the known document title aliases
	 *
	 * The method is case sensitive.
	 * 
	 * @param  string $title  The title to remove
	 * @return KlinkDocumentDescriptor
	 */
	public function removeTitleAlias($title)
	{
		if(!empty($title) && !empty($this->titleAliases) && in_array($title, $this->titleAliases)) {
			$this->titleAliases = array_values(array_diff($this->titleAliases, array($title)));
		}

		return $this;
	}

	/**
	 * setTitleAliases
	 * @param string[] $value
	 * @return void
	 */
	public function setTitleAliases($value) {
		$this->titleAliases = $value;
	}
	/**
	 * getTitleAliases
	 * @return string[]
	 */
	public function getTitleAliases() {
		return $this->titleAliases;
	}

	// ---

	

	/**
	 * The parameter-less constructor is used for deserialization 
	 * purposes only and might be deprecated in future versions
	 * @internal
	 */
	function __construct(){

	}

	/**
	 * Build an instance of KlinkDocumentDescriptor
	 * @param  string $institutionID   The id of the institution that owns the document
	 * @param  string $localDocumentID The identifier of the document inside the Adapter
	 * @param  string $hash            The SHA-2 hash of the document content. Use KlinkDocumentUtils::generateDocumentHash() to get the correct value.
	 * @param  string $title           The title of the document
	 * @param  string $mimetype        The mime-type of the document. Use KlinkDocumentUtils::get_mime() to get the correct value.
	 * @param  string $documentURI     The public URL of the document's content
	 * @param  string $thumbnailURI    The public URL of the document's thumbnail
	 * @param  string $userUploader    The user that has uploaded the document
	 * @param  string $userOwner       The user that owns the document
	 * @param  string|null $visibility      The document visibility, the default is KlinkVisibilityType::KLINK_PUBLIC
	 * @param  string|null $creationDate    The document creation date in RFC3339 format, the default value is the current timestamp
	 * @return [type]                  [description]
	 * @throws InvalidArgumentException If one or more parameters are invalid
	 */
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
        
        KlinkHelpers::is_valid_url($documentURI);

        KlinkHelpers::is_valid_url($thumbnailURI);


        $instance = new self();

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