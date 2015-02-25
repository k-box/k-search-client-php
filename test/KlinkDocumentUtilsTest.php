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
		return array(

		array('jpg', 'image/jpeg'),
		array('gif', 'image/gif'),
		array('png', 'image/png'),
		array('bmp', 'image/bmp'),
		array('tif', 'image/tiff'),
		array('ico', 'image/x-icon'),
		array('asf', 'video/x-ms-asf'),
		array('wmv', 'video/x-ms-wmv'),
		array('wmx', 'video/x-ms-wmx'),
		array('wm', 'video/x-ms-wm'),
		array('avi', 'video/avi'),
		array('divx', 'video/divx'),
		array('flv', 'video/x-flv'),
		array('mov', 'video/quicktime'),
		array('mpeg', 'video/mpeg'),
		array('mp4', 'video/mp4'),
		array('ogv', 'video/ogg'),
		array('webm', 'video/webm'),
		array('mkv', 'video/x-matroska'),
		array('3gp', 'video/3gpp'),
		array('3g2', 'video/3gpp2'),
		array('txt', 'text/plain'),
		array('csv', 'text/csv'),
		array('tsv', 'text/tab-separated-values'),
		array('ics', 'text/calendar'),
		array('rtx', 'text/richtext'),
		array('css', 'text/css'),
		array('html', 'text/html'),
		array('vtt', 'text/vtt'),
		array('dfxp', 'application/ttaf+xml'),
		array('mp3', 'audio/mpeg'),
		array('ra', 'audio/x-realaudio'),
		array('wav', 'audio/wav'),
		array('ogg', 'audio/ogg'),
		array('mid', 'audio/midi'),
		array('wma', 'audio/x-ms-wma'),
		array('wax', 'audio/x-ms-wax'),
		array('mka', 'audio/x-matroska'),
		array('rtf', 'application/rtf'),
		array('js', 'application/javascript'),
		array('pdf', 'application/pdf'),
		array('swf', 'application/x-shockwave-flash'),
		array('class', 'application/java'),
		array('tar', 'application/x-tar'),
		array('zip', 'application/zip'),
		array('gz', 'application/x-gzip'),
		array('rar', 'application/rar'),
		array('7z', 'application/x-7z-compressed'),
		array('exe', 'application/x-msdownload'),
		array('doc', 'application/msword'),
		array('pot', 'application/vnd.ms-powerpoint'),
		array('wri', 'application/vnd.ms-write'),
		array('xls', 'application/vnd.ms-excel'),
		array('mdb', 'application/vnd.ms-access'),
		array('mpp', 'application/vnd.ms-project'),
		array('docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
		array('docm', 'application/vnd.ms-word.document.macroEnabled.12'),
		array('dotx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.template'),
		array('dotm', 'application/vnd.ms-word.template.macroEnabled.12'),
		array('xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
		array('xlsm', 'application/vnd.ms-excel.sheet.macroEnabled.12'),
		array('xlsb', 'application/vnd.ms-excel.sheet.binary.macroEnabled.12'),
		array('xltx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.template'),
		array('xltm', 'application/vnd.ms-excel.template.macroEnabled.12'),
		array('xlam', 'application/vnd.ms-excel.addin.macroEnabled.12'),
		array('pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'),
		array('pptm', 'application/vnd.ms-powerpoint.presentation.macroEnabled.12'),
		array('ppsx', 'application/vnd.openxmlformats-officedocument.presentationml.slideshow'),
		array('ppsm', 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12'),
		array('potx', 'application/vnd.openxmlformats-officedocument.presentationml.template'),
		array('potm', 'application/vnd.ms-powerpoint.template.macroEnabled.12'),
		array('ppam', 'application/vnd.ms-powerpoint.addin.macroEnabled.12'),
		array('sldx', 'application/vnd.openxmlformats-officedocument.presentationml.slide'),
		array('sldm', 'application/vnd.ms-powerpoint.slide.macroEnabled.12'),
		array('onetoc', 'application/onenote'),
		array('oxps', 'application/oxps'),
		array('xps', 'application/vnd.ms-xpsdocument'),
		array('odt', 'application/vnd.oasis.opendocument.text'),
		array('odp', 'application/vnd.oasis.opendocument.presentation'),
		array('ods', 'application/vnd.oasis.opendocument.spreadsheet'),
		array('odg', 'application/vnd.oasis.opendocument.graphics'),
		array('odc', 'application/vnd.oasis.opendocument.chart'),
		array('odb', 'application/vnd.oasis.opendocument.database'),
		array('odf', 'application/vnd.oasis.opendocument.formula'),
		array('wp', 'application/wordperfect'),
		array('key', 'application/vnd.apple.keynote'),
		array('numbers', 'application/vnd.apple.numbers'),
		array('pages', 'application/vnd.apple.pages'),

		);
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