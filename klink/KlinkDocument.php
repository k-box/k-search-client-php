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

	private $file;

	/**
	 * 
	 */
	public function __constructor(KlinkDocumentDescriptor $descriptor, $file_path){
		$this->descriptor = $descriptor;

		$this->file = $file_path;
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
	public function getFile() {
		return $this->file;
	}

}