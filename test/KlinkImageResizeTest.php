<?php

/**
* Test the KlinkKlinkImageResize Class for basic functionality
* 
* @requires extension gd
* @requires extension exif
*/
class KlinkImageResizeTest extends PHPUnit_Framework_TestCase 
{
	public function setUp()
	{
	  	date_default_timezone_set('America/Los_Angeles');

        error_reporting(E_ALL & E_STRICT);

	}


	private $image_types = array(
        'gif',
        'jpeg',
        'png'
    );

    private $unsupported_image = 'Qk08AAAAAAAAADYAAAAoAAAAAQAAAAEAAAABABAAAAAAAAYAAAASCwAAEgsAAAAAAAAAAAAA/38AAAAA';
    private $image_string = 'R0lGODlhDwAPAKECAAAAzMzM/////wAAACwAAAAADwAPAAACIISPeQHsrZ5ModrLlN48CXF8m2iQ3YmmKqVlRtW4MLwWACH+H09wdGltaXplZCBieSBVbGVhZCBTbWFydFNhdmVyIQAAOw==';

    public function testLoadString(){
        $resize = KlinkImageResize::createFromString(base64_decode($this->image_string));

        $this->assertEquals(IMAGETYPE_GIF, $resize->source_type);
        $this->assertInstanceOf('KlinkImageResize', $resize);
    }

    public function testLoadGif() {
        $image = $this->createImage(1, 1, 'gif');
        $resize = KlinkImageResize::createFromFile($image);

        $this->assertEquals(IMAGETYPE_GIF, $resize->source_type);
        $this->assertInstanceOf('KlinkImageResize', $resize);
    }

    public function testLoadJpg() {
        $image = $this->createImage(1, 1, 'jpeg');
        $resize = KlinkImageResize::createFromFile($image);

        $this->assertEquals(IMAGETYPE_JPEG, $resize->source_type);
        $this->assertInstanceOf('KlinkImageResize', $resize);
    }

    public function testLoadPng() {
        $image = $this->createImage(1, 1, 'png');
        $resize = KlinkImageResize::createFromFile($image);

        $this->assertEquals(IMAGETYPE_PNG, $resize->source_type);
        $this->assertInstanceOf('KlinkImageResize', $resize);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Could not load image from string
     */
    public function testInvalidString(){
        KlinkImageResize::createFromString($this->unsupported_image);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Filename cannot be empty
     */
    public function testLoadNoFile() {
        KlinkImageResize::createFromFile(null);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Could not read
     */
    public function testLoadUnsupportedFile() {
        new KlinkImageResize(__FILE__);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Unsupported image type
     */
    public function testLoadUnsupportedImage() {
        $filename = $this->getTempFile();

        $image = fopen($filename, 'w');
        fwrite($image, base64_decode($this->unsupported_image));
        fclose($image);

        new KlinkImageResize($filename);
    }

    public function testResizeToHeight() {
        $image = $this->createImage(200, 100, 'png');
        $resize = new KlinkImageResize($image);

        $resize->resizeToHeight(50);

        $this->assertEquals(100, $resize->getDestWidth());
        $this->assertEquals(50, $resize->getDestHeight());
    }

    public function testResizeToWidth() {
        $image = $this->createImage(200, 100, 'png');
        $resize = new KlinkImageResize($image);

        $resize->resizeToWidth(100);

        $this->assertEquals(100, $resize->getDestWidth());
        $this->assertEquals(50, $resize->getDestHeight());
    }

    public function testScale() {
        $image = $this->createImage(200, 100, 'png');
        $resize = new KlinkImageResize($image);

        $resize->scale(50);

        $this->assertEquals(100, $resize->getDestWidth());
        $this->assertEquals(50, $resize->getDestHeight());
    }

    public function testResize() {
        $image = $this->createImage(200, 100, 'png');
        $resize = new KlinkImageResize($image);

        $resize->resize(50, 50);

        $this->assertEquals(50, $resize->getDestWidth());
        $this->assertEquals(50, $resize->getDestHeight());
    }

    public function testResizeLargerNotAllowed() {
        $image = $this->createImage(200, 100, 'png');
        $resize = new KlinkImageResize($image);

        $resize->resize(400, 200);

        $this->assertEquals(200, $resize->getDestWidth());
        $this->assertEquals(100, $resize->getDestHeight());
    }

    public function testCrop() {
        $image = $this->createImage(200, 100, 'png');
        $resize = new KlinkImageResize($image);

        $resize->crop(50, 50);

        $this->assertEquals(50, $resize->getDestWidth());
        $this->assertEquals(50, $resize->getDestHeight());
    }

    public function testCropPosition() {
        $image = $this->createImage(200, 100, 'png');
        $resize = new KlinkImageResize($image);

        $resize->crop(50, 50, false, $resize::cropRIGHT);

        $reflection_class = new ReflectionClass('KlinkImageResize');
        $source_x = $reflection_class->getProperty('source_x');
        $source_x->setAccessible(true);

        $this->assertEquals(100, $source_x->getValue($resize));
    }

    public function testCropLargerNotAllowed() {
        $image = $this->createImage(200, 100, 'png');
        $resize = new KlinkImageResize($image);

        $resize->crop(500, 500);

        $this->assertEquals(200, $resize->getDestWidth());
        $this->assertEquals(100, $resize->getDestHeight());
    }

    public function testSaveGif() {
        $image = $this->createImage(200, 100, 'gif');

        $resize = new KlinkImageResize($image);

        $filename = $this->getTempFile();

        $resize->save($filename);

        $this->assertEquals(IMAGETYPE_GIF, exif_imagetype($filename));
    }

    public function testSaveJpg() {
        $image = $this->createImage(200, 100, 'jpeg');

        $resize = new KlinkImageResize($image);

        $filename = $this->getTempFile();

        $resize->save($filename);

        $this->assertEquals(IMAGETYPE_JPEG, exif_imagetype($filename));
    }

    public function testSavePng() {
        $image = $this->createImage(200, 100, 'png');

        $resize = new KlinkImageResize($image);

        $filename = $this->getTempFile();

        $resize->save($filename);

        $this->assertEquals(IMAGETYPE_PNG, exif_imagetype($filename));
    }


    public function testGet(){
        $resize = KlinkImageResize::createFromString(base64_decode($this->image_string));
        $image = $resize->get();
        $this->assertEquals(79, strlen($image));
    }

    public function testToString(){
        $resize = KlinkImageResize::createFromString(base64_decode($this->image_string));
        $image = (string)$resize;
        $this->assertEquals(79, strlen($image));
    }

    /**
     * @requires function imagecreatefromgif
     * @requires function imagegif
     * @requires function finfo_open
     */
    public function testOutputGif() {
        $image = $this->createImage(200, 100, 'gif');

        $resize = new KlinkImageResize($image);

        ob_start();

        // supressing header errors
        @$resize->output();

        $image_contents = ob_get_clean();

        $info = finfo_open();

        $type = finfo_buffer($info, $image_contents, FILEINFO_MIME_TYPE);

        $this->assertEquals('image/gif', $type);
    }

    /**
     * @requires function finfo_open
     */
    public function testOutputJpg() {
        $image = $this->createImage(200, 100, 'jpeg');

        $resize = new KlinkImageResize($image);

        ob_start();

        // supressing header errors
        @$resize->output();

        $image_contents = ob_get_clean();

        $info = finfo_open();

        $type = finfo_buffer($info, $image_contents, FILEINFO_MIME_TYPE);

        $this->assertEquals('image/jpeg', $type);
    }

    /**
     * @requires function imagecreatefrompng
     * @requires function finfo_open
     */
    public function testOutputPng() {
        $image = $this->createImage(200, 100, 'png');

        $resize = new KlinkImageResize($image);

        ob_start();

        // supressing header errors
        @$resize->output();

        $image_contents = ob_get_clean();

        $info = finfo_open();

        $type = finfo_buffer($info, $image_contents, FILEINFO_MIME_TYPE);

        $this->assertEquals('image/png', $type);
    }

    private function createImage($width, $height, $type) {
        if (!in_array($type, $this->image_types)) {
            throw new \Exception('Unsupported image type');
        }

        $image = imagecreatetruecolor($width, $height);

        $filename = $this->getTempFile();

        $output_function = 'image' . $type;
        $output_function($image, $filename);

        return $filename;
    }

    private function getTempFile() {
        return tempnam(sys_get_temp_dir(), 'resize_test_image');
    }


}