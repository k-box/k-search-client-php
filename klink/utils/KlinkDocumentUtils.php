<?php


/**
* 
*/
class KlinkDocumentUtils
{
	
	private static $mimeTypesToDocType = array(

		'post' => 'web-page',
		'page' => 'web-page',
		'node' => 'web-page',
		'application/msword' => 'document',
		'application/vnd.ms-excel' => 'spreadsheet',
		'application/vnd.ms-powerpoint' => 'presentation',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'spreadsheet',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'presentation',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'document',
		'application/pdf' => 'document'

		);




	/**
	 * Computes the hash for uniquely identify the file
	 * Uses SHA-512 variant of SHA-2 (Secure hash Algorithm)
	 * @param string $filePath The file path
	 * @return string
	 */
	public static function generateDocumentHash( $filePath )
	{

		return hash_file( 'sha512', $filePath );

	}

	/**
	 * Computes the SHA-512 hash for the specified content
	 * @param string $content 
	 * @return string
	 */
	public static function generateHash( $content ){

		return hash( 'sha512', $content );

	}


	public static function areDocumentsTheSame($fileOne, $fileTwo){

		return self::generateDocumentHash( $fileOne ) === self::generateDocumentHash( $fileTwo );

	}


	public static function generateDocumentThumbnail($file, array $sizes = null){

	}


	public static function isLocalDocument(KlinkDocumentDescriptor $descriptor, $instId, $adapterId){


		return $descriptor->getInstitutionId() === $instId;
		


		
		
	}

	/**
	 * Convert the mime type to a document type
	 * @param string $mimeType 
	 * @return string the correspondent 
	 */
	public static function documentTypeFromMimeType( $mimeType ){

		if( array_key_exists($mimeType, self::$mimeTypesToDocType) ) {
			return self::$mimeTypesToDocType[$mimeType];
		}

		return "document";
	}

	/**
	 * Check if the specified mime type is one of the supported mimetypes for indexing
	 * @param type $mimeType 
	 * @return type
	 */
	public static function isMimeTypeSupported( $mimeType ){

		return array_key_exists($mimeType, self::$mimeTypesToDocType);

	}

	

}