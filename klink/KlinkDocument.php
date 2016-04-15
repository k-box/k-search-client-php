<?php


/**
 * Describe a Document to be sent to the K-Link Core for indexing purposes
 */
class KlinkDocument {

	/**
	 * descriptor
	 * @var KlinkDocumentDescriptor
	 */

	protected $descriptor;

	/**
	 * content, stream or file path
	 * @var mixed
	 */

	protected $documentData;

	/**
	 * Create an instance of a KlinkDocument
	 *
	 * @param KlinkDocumentDescriptor $descriptor The descriptor of the document
	 * @param mixed $data The document data as string, stream (please don't close it until when we have done) or the absolute file path of the document content
	 */
	public function __construct(KlinkDocumentDescriptor $descriptor, $data){

		$this->descriptor = $descriptor;

		$this->documentData = $data;
	}

	/**
	 * Returns the KlinkDocument descriptor for this document
	 *
	 * @return KlinkDocumentDescriptor
	 */
	public function getDescriptor() {

		return $this->descriptor;
	}

	/**
	 * Tells if the document data, hold by this instance, is a file on disk
	 *
	 * @return boolean true if the data hold is a file, false otherwise
	 */
	function isFile(){
		
		if(!empty($this->documentData)){
			return false;
		}
		
		if(is_resource($this->documentData)){
			return false;
		}
		
		return @is_file($this->documentData);
	}
	
	/**
	 * Return the internal representation of the document data, as passed in the constructor
	 * @return mixed the document data
	 */
	public function getOriginalDocumentData(){
		return $this->documentData;
	}

	/**
	 * Get the full document content as a base64 string
	 *
	 * If you created the KlinkDocument passing a stream please use getDocumentStream() or getDocumentBase64Stream(). This method will use more RAM to do the same operation.
	 *
	 * @return string the base64 encoded content of the document, if a file was passed as content the encoded content of the file is returned
	 */
	public function getDocumentData() {

		if(is_resource($this->documentData) && @get_resource_type($this->documentData) === 'stream'){
			
			// THIS solution is not the best for memory consumption
			return base64_encode(stream_get_contents($this->documentData));
		}

		if($this->isFile()){

			return base64_encode( file_get_contents( $this->documentData ) );

		}

		return base64_encode( $this->documentData );
	}
    
    /**
	 * Get the content of the document as a stream
	 *
	 * This method returns a stream, so be sure to close it when you are done.
	 *
	 * @return stream the document content as a raw readonly stream
	 */
	public function getDocumentStream() {
		
		if( is_resource($this->documentData) && @get_resource_type($this->documentData) === 'stream' ){
			return $this->documentData;
		}
		
		if($this->isFile()){
			return fopen($this->documentData, 'r');
		}
		
		return fopen('data://text/plain,' . $this->documentData, 'r');
		
	}
    
    /**
	 * Get the content of the document, encoded as base64, as a stream
	 *
	 * This method returns a stream, so be sure to close it when you are done.
	 *
	 * @return stream the document content as a raw stream
	 */
    public function getDocumentBase64Stream(){
		
		if( is_resource($this->documentData) && @get_resource_type($this->documentData) === 'stream' ){
			// TODO: apply base64 encode
			return $this->documentData;
		}
		
		if($this->isFile()){
			return fopen('php://filter/read=convert.base64-encode/resource=' . $this->documentData,'r');
		}
        
        return fopen('data://text/plain,' . base64_encode($this->documentData), 'r');
		
    }

}