<?php



/**
 * KlinkDocument
 */

class KlinkDocument {


	/**
	 * descriptor
	 * @var KlinkDocumentDescriptor
	 */

	protected $descriptor;

	/**
	 * file
	 * @var mixed
	 */

	protected $documentData;

	/**
	 * 
	 */
	public function __construct(KlinkDocumentDescriptor $descriptor, $data){

		$this->descriptor = $descriptor;

		$this->documentData = $data;
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
	 * @return string the base64 encoded content of the document, if a file was passed as content the encoded content of the file is returned
	 */
	public function getDocumentData() {

		if(@is_file($this->documentData)){

			return base64_encode( file_get_contents( $this->documentData ) );

		}

		return base64_encode( $this->documentData );
	}

}