<?php



/**
 * KlinkDocument
 */

class KlinkDocument {


	/**
	 * descriptor
	 * @var KlinkDocumentDescriptor
	 */

	private $descriptor;

	/**
	 * file
	 * @var mixed
	 */

	private $documentData;

	/**
	 * 
	 */
	public function __constructor(KlinkDocumentDescriptor $descriptor, $file_path){
		$this->descriptor = $descriptor;

		$this->documentData = $file_path; //TODO: base64 encoding of file content if the mimetype is not text/plain, otherwise file content
	}



	/**
	 * getDescriptor
	 * @return KlinkDocumentDescriptor
	 */
	public function getDescriptor() {
		return $this->descriptor;
	}

	

	/**
	 * getFile
	 * @return string the file path on the accessible filesystem
	 */
	public function getDocumentData() {
		return $this->documentData;
	}

}