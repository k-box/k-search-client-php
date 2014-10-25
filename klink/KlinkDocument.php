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

		$this->documentData = $file_path;
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

		if(is_file($this->documentData)){

			return base64_encode( file_get_contents( $this->documentData ) );

		}

		return $this->documentData;
	}

}