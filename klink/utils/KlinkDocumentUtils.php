<?php


/**
* Utility functions to handle specific operations on documents given the file path
*/
class KlinkDocumentUtils
{
	
	private static $mimeTypesToDocType = array(

		'post' => 'web-page',
		'page' => 'web-page',
		'node' => 'web-page',
		'text/html' => 'web-page',
		'application/msword' => 'document',
		'application/vnd.ms-excel' => 'spreadsheet',
		'application/vnd.ms-powerpoint' => 'presentation',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'spreadsheet',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'presentation',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'document',
		'application/pdf' => 'document',
		'text/uri-list' => 'uri-list',
		'image/jpg' => 'image',
		'image/jpeg' => 'image',
		'image/gif' => 'image',
		'image/png' => 'image',
		'image/tiff' => 'image',

		);


	private static $fileExtensionToMimeType = array(
		// Image formats.
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'bmp' => 'image/bmp',
		'tif|tiff' => 'image/tiff',
		'ico' => 'image/x-icon',
		// Video formats.
		'asf|asx' => 'video/x-ms-asf',
		'wmv' => 'video/x-ms-wmv',
		'wmx' => 'video/x-ms-wmx',
		'wm' => 'video/x-ms-wm',
		'avi' => 'video/avi',
		'divx' => 'video/divx',
		'flv' => 'video/x-flv',
		'mov|qt' => 'video/quicktime',
		'mpeg|mpg|mpe' => 'video/mpeg',
		'mp4|m4v' => 'video/mp4',
		'ogv' => 'video/ogg',
		'webm' => 'video/webm',
		'mkv' => 'video/x-matroska',
		'3gp|3gpp' => 'video/3gpp', // Can also be audio
		'3g2|3gp2' => 'video/3gpp2', // Can also be audio
		// Text formats.
		'txt|asc|c|cc|h|srt' => 'text/plain',
		'csv' => 'text/csv',
		'tsv' => 'text/tab-separated-values',
		'ics' => 'text/calendar',
		'rtx' => 'text/richtext',
		'css' => 'text/css',
		'html|htm' => 'text/html',
		'vtt' => 'text/vtt',
		'dfxp' => 'application/ttaf+xml',
		// Audio formats.
		'mp3|m4a|m4b' => 'audio/mpeg',
		'ra|ram' => 'audio/x-realaudio',
		'wav' => 'audio/wav',
		'ogg|oga' => 'audio/ogg',
		'mid|midi' => 'audio/midi',
		'wma' => 'audio/x-ms-wma',
		'wax' => 'audio/x-ms-wax',
		'mka' => 'audio/x-matroska',
		// Misc application formats.
		'rtf' => 'application/rtf',
		'js' => 'application/javascript',
		'pdf' => 'application/pdf',
		'swf' => 'application/x-shockwave-flash',
		'class' => 'application/java',
		'tar' => 'application/x-tar',
		'zip' => 'application/zip',
		'gz|gzip' => 'application/x-gzip',
		'rar' => 'application/rar',
		'7z' => 'application/x-7z-compressed',
		'exe' => 'application/x-msdownload',
		// MS Office formats.
		'doc' => 'application/msword',
		'pot|pps|ppt' => 'application/vnd.ms-powerpoint',
		'wri' => 'application/vnd.ms-write',
		'xls|xla|xlt|xlw' => 'application/vnd.ms-excel',
		'mdb' => 'application/vnd.ms-access',
		'mpp' => 'application/vnd.ms-project',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
		'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
		'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
		'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
		'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
		'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
		'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
		'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
		'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
		'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
		'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
		'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
		'oxps' => 'application/oxps',
		'xps' => 'application/vnd.ms-xpsdocument',
		// OpenOffice formats.
		'odt' => 'application/vnd.oasis.opendocument.text',
		'odp' => 'application/vnd.oasis.opendocument.presentation',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		'odg' => 'application/vnd.oasis.opendocument.graphics',
		'odc' => 'application/vnd.oasis.opendocument.chart',
		'odb' => 'application/vnd.oasis.opendocument.database',
		'odf' => 'application/vnd.oasis.opendocument.formula',
		// WordPerfect formats.
		'wp|wpd' => 'application/wordperfect',
		// iWork formats.
		'key' => 'application/vnd.apple.keynote',
		'numbers' => 'application/vnd.apple.numbers',
		'pages' => 'application/vnd.apple.pages',
		'uri' => 'text/uri-list'
		) ;




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
	 * 
	 * @param string $mimeType the mime type to check for
	 * @return boolean true if supported, false otherwise
	 */
	public static function isMimeTypeSupported( $mimeType ){

		return array_key_exists($mimeType, self::$mimeTypesToDocType);

	}

	public static function getDocumentTypes(){
		return array_values(self::$mimeTypesToDocType);
	}

	/**
	 * Return the file extension that corresponds to the given mime type
	 * @param  string $mimeType the mime-type of the file
	 * @return string           the known file extension
	 * @throws InvalidArgumentException If the mime type is unkwnown, null or empty
	 */
	public static function getExtensionFromMimeType( $mimeType ){
		KlinkHelpers::is_string_and_not_empty( $mimeType, 'mime type' );

		$inverted = array_flip( self::$fileExtensionToMimeType );

		$key = array_key_exists($mimeType, $inverted);

		if( $key ){

			$ext = $inverted[$mimeType];

			$pos = strpos($ext, '|');
			if( $pos !== false ){
				$ext = substr( $ext, 0, $pos);
			}

			return $ext;
		}

		throw new InvalidArgumentException("Unknown mime type.");

	}

	/**
	 * Get the mime type of the specified file
	 * 
	 * @param string $file the path of the file to get the mime type
	 * 
	 * @return string|boolean the mime type or false in case of error
	 */
	public static function get_mime($file) {
		if (function_exists("finfo_file")) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
			$mime = finfo_file($finfo, $file);
			finfo_close($finfo);
			return $mime;
		} else if (function_exists("mime_content_type")) {
			return mime_content_type($file);
		} else {

			$extension = pathinfo( $file, PATHINFO_EXTENSION );

			if ( !empty( $extension ) ) {

				foreach ( self::$fileExtensionToMimeType as $exts => $mime ) {
	                    if ( preg_match( '!^(' . $exts . ')$!i', $extension ) ) {
	                            return $mime;
	                    }
	            }

        	}

			return false;
		}
	}

	

}