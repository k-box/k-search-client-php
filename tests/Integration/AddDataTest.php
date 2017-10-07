<?php
namespace Tests\Integration;

use Tests\TestCase;
use KSearchClient\Client;
use KSearchClient\Model\Data\Data;
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

    public function testClientCanAddData()
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

    // /**
    //  * @depends testClientCanAddData
    //  */
    // public function testClientCanAddVideo()
    // {
    //     $auth = new Authentication('token', 'http://localhost');

    //     $service_url = getenv('KSEARCH_URL');

    //     $client = Client::build($service_url, $auth);

    //     $uuid = Uuid::uuid4()->toString();

    //     $added_data = $client->add($this->createDataModel($uuid), 'textual content to use');

    //     var_dump($added_data);

    //     $this->assertInstanceOf(Data::class, $added_data);

    //     // cc1bbc0b-20e8-4e1f-b894-fb067e81c5dd
    //     // 00000000-0000-0000-0000-000000000001
    // }
}