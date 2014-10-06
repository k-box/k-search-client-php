<?php namespace Klink\Utils;


/**
* 
*/
class DocumentUtils
{
	
	/**
	* Computes the hash for uniquely identify the file
	* Uses SHA-512 variant of SHA-2 (Secure hash Algorithm)
	*/
	public static function generateDocumentHash( $filePath )
	{

		return hash_file( 'sha512', $filePath );

	}

	public static function generateHash( $content ){

		return hash( 'sha512', $content );

	}


	public static function areDocumentsTheSame($fileOne, $fileTwo){

		return self::generateDocumentHash( $fileOne ) === self::generateDocumentHash( $fileTwo );

	}



	public static function generateDocumentThumbnail($file, array $sizes){

	}


}