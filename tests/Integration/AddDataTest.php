<?php
namespace Tests\Integration;

use Tests\TestCase;
use KSearchClient\Client;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\GeographicGeometry;
use KSearchClient\Http\Authentication;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Exception\InvalidDataException;
use Tests\Concern\SetupIntegrationTest;

class AddDataTest extends TestCase
{
    use SetupIntegrationTest;

    public function testClientCanAddDataWithTextualContent()
    {
        $uuid = Uuid::uuid4()->toString();

        $to_add = $this->createDataModel($uuid);

        $added_data = $this->client->add($to_add, 'textual content to use');

        $this->assertInstanceOf(Data::class, $added_data);

        $this->assertEquals($to_add, $added_data);
    }

    public function testAddThrowsErrorIfInvalidUUID()
    {
        $exceptionHandled = false;
        try{
    
            $uuid = '00000000-0000-0000-0000-000000000001';
    
            $added_data = $this->client->add($this->createDataModel($uuid), 'textual content to use');

        }catch(InvalidDataException $ex){

            $this->assertEquals(400, $ex->getCode());

            $this->assertContains('params.data.uuid', $ex->getMessage());

            $this->assertArrayHasKey('params.data.uuid', $ex->getData());

            $exceptionHandled = true;
        }

        $this->assertTrue($exceptionHandled, 'Expected ErrorResponseException, but got nothing');
    }

    public function testAddThrowsErrorIfInvalidDataProperties()
    {
        $exceptionHandled = false;
        try{
    
            $uuid = Uuid::uuid4()->toString();

            $data = tap($this->createDataModel($uuid), function($model){
                $model->properties->title = '';
                $model->uploader = null;
            });
    
            $added_data = $this->client->add($data, 'textual content to use');

        }catch(InvalidDataException $ex){

            $this->assertEquals(400, $ex->getCode());

            $this->assertContains('params.data.properties.title', $ex->getMessage());
            $this->assertArrayHasKey('params.data.properties.title', $ex->getData());
            $this->assertContains('params.data.uploader', $ex->getMessage());
            $this->assertArrayHasKey('params.data.uploader', $ex->getData());

            $exceptionHandled = true;
        }

        $this->assertTrue($exceptionHandled, 'Expected ErrorResponseException, but got nothing');
    }

    public function testAddThrowsErrorIfInvalidGeoLocation()
    {
        $this->skipIfApiVersionNotEqual('3.5');

        $exceptionHandled = false;
        try{
    
            $uuid = Uuid::uuid4()->toString();

            $data = tap($this->createDataModel($uuid), function($model){
                $model->geo_location = GeographicGeometry::polygon([]);
            });
    
            $added_data = $this->client->add($data, 'textual content to use');

            $this->fail('Expected InvalidDataException, but got nothing');

        }catch(InvalidDataException $ex){

            $this->assertEquals(400, $ex->getCode());

            $this->assertContains('params.data.geo_location', $ex->getMessage());
            $this->assertArrayHasKey('params.data.geo_location', $ex->getData());
        }
    }

    public function test_add_geo_location()
    {
        $this->skipIfApiVersionNotEqual('3.5');
    
        $uuid = Uuid::uuid4()->toString();

        $geometry = GeographicGeometry::point(-105.01621,39.57422);
        $data = tap($this->createDataModel($uuid), function($model) use($geometry){
            $model->geo_location = $geometry;
        });

        $added_data = $this->client->add($data, 'textual content to use');
        
        $this->assertInstanceOf(Data::class, $added_data);
        $this->assertEquals($data, $added_data);
        $this->assertEquals($geometry->__toString(), $added_data->geo_location);

    }

    public function testAddThrowsErrorIfInvalidUrlIsUsed()
    {
        try{
    
            $uuid = Uuid::uuid4()->toString();

            $data = $this->createDataModel($uuid, 'http://localhost:3000/test.pdf');
    
            $added_data = $this->client->add($data, null);

            $this->fail("No ErrorResponseException received");

        }catch(ErrorResponseException $ex){

            $this->assertEquals(400, $ex->getCode());

            $this->assertContains('Unable to download contents', $ex->getMessage());
            $this->assertCount(1, $ex->getData());
            $this->assertContains('cURL error 7: Failed to connect to localhost port 3000: Connection refused (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)', $ex->getData());
            
        }
    }

}