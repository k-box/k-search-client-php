<?php

/**
* Test the KlinkHttp Class for basic functionality
*/
class KlinkDocumentUtilsTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	
	}

	public function mimeInput()
	{
		return [

		['jpg', 'image/jpeg'],
		['gif', 'image/gif'],
		['png', 'image/png'],
		['bmp', 'image/bmp'],
		['tif', 'image/tiff'],
		['ico', 'image/x-icon'],
		['asf', 'video/x-ms-asf'],
		['wmv', 'video/x-ms-wmv'],
		['wmx', 'video/x-ms-wmx'],
		['wm', 'video/x-ms-wm'],
		['avi', 'video/avi'],
		['divx', 'video/divx'],
		['flv', 'video/x-flv'],
		['mov', 'video/quicktime'],
		['mpeg', 'video/mpeg'],
		['mp4', 'video/mp4'],
		['ogv', 'video/ogg'],
		['webm', 'video/webm'],
		['mkv', 'video/x-matroska'],
		['3gp', 'video/3gpp'],
		['3g2', 'video/3gpp2'],
		['txt', 'text/plain'],
		['csv', 'text/csv'],
		['tsv', 'text/tab-separated-values'],
		['ics', 'text/calendar'],
		['rtx', 'text/richtext'],
		['css', 'text/css'],
		['html', 'text/html'],
		['vtt', 'text/vtt'],
		['dfxp', 'application/ttaf+xml'],
		['mp3', 'audio/mpeg'],
		['ra', 'audio/x-realaudio'],
		['wav', 'audio/wav'],
		['ogg', 'audio/ogg'],
		['mid', 'audio/midi'],
		['wma', 'audio/x-ms-wma'],
		['wax', 'audio/x-ms-wax'],
		['mka', 'audio/x-matroska'],
		['rtf', 'application/rtf'],
		['js', 'application/javascript'],
		['pdf', 'application/pdf'],
		['swf', 'application/x-shockwave-flash'],
		['class', 'application/java'],
		['tar', 'application/x-tar'],
		['zip', 'application/zip'],
		['gz', 'application/x-gzip'],
		['rar', 'application/rar'],
		['7z', 'application/x-7z-compressed'],
		['exe', 'application/x-msdownload'],
		['doc', 'application/msword'],
		['pot', 'application/vnd.ms-powerpoint'],
		['wri', 'application/vnd.ms-write'],
		['xls', 'application/vnd.ms-excel'],
		['mdb', 'application/vnd.ms-access'],
		['mpp', 'application/vnd.ms-project'],
		['docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
		['docm', 'application/vnd.ms-word.document.macroEnabled.12'],
		['dotx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.template'],
		['dotm', 'application/vnd.ms-word.template.macroEnabled.12'],
		['xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
		['xlsm', 'application/vnd.ms-excel.sheet.macroEnabled.12'],
		['xlsb', 'application/vnd.ms-excel.sheet.binary.macroEnabled.12'],
		['xltx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.template'],
		['xltm', 'application/vnd.ms-excel.template.macroEnabled.12'],
		['xlam', 'application/vnd.ms-excel.addin.macroEnabled.12'],
		['pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'],
		['pptm', 'application/vnd.ms-powerpoint.presentation.macroEnabled.12'],
		['ppsx', 'application/vnd.openxmlformats-officedocument.presentationml.slideshow'],
		['ppsm', 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12'],
		['potx', 'application/vnd.openxmlformats-officedocument.presentationml.template'],
		['potm', 'application/vnd.ms-powerpoint.template.macroEnabled.12'],
		['ppam', 'application/vnd.ms-powerpoint.addin.macroEnabled.12'],
		['sldx', 'application/vnd.openxmlformats-officedocument.presentationml.slide'],
		['sldm', 'application/vnd.ms-powerpoint.slide.macroEnabled.12'],
		['onetoc', 'application/onenote'],
		['oxps', 'application/oxps'],
		['xps', 'application/vnd.ms-xpsdocument'],
		['odt', 'application/vnd.oasis.opendocument.text'],
		['odp', 'application/vnd.oasis.opendocument.presentation'],
		['ods', 'application/vnd.oasis.opendocument.spreadsheet'],
		['odg', 'application/vnd.oasis.opendocument.graphics'],
		['odc', 'application/vnd.oasis.opendocument.chart'],
		['odb', 'application/vnd.oasis.opendocument.database'],
		['odf', 'application/vnd.oasis.opendocument.formula'],
		['wp', 'application/wordperfect'],
		['key', 'application/vnd.apple.keynote'],
		['numbers', 'application/vnd.apple.numbers'],
		['pages', 'application/vnd.apple.pages']

		];
	}

	
	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object    Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array  $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod(&$object, $methodName, array $parameters = array())
	{
	    $reflection = new \ReflectionClass(get_class($object));
	    $method = $reflection->getMethod($methodName);
	    $method->setAccessible(true);

	    return $method->invokeArgs($object, $parameters);
	}

 
	/**
	 * [testGetExtensionFromMimeType description]
	 * @param  [type] $expected [description]
	 * @param  [type] $mimeType [description]
	 * @return [type]           [description]
	 *
	 * @dataProvider mimeInput
	 */
 	public function testGetExtensionFromMimeType( $expected, $mimeType )
 	{

 		$actual = KlinkDocumentUtils::getExtensionFromMimeType( $mimeType );

 		$this->assertEquals( $expected, $actual);
 		
 	}


}