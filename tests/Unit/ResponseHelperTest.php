<?php

namespace Tests\Unit;

use Tests\TestCase;
use KSearchClient\Model\Error\Error;
use KSearchClient\Model\Status\Status;
use KSearchClient\Http\ResponseHelper;
use KSearchClient\Model\Error\ErrorResponse;
use KSearchClient\Model\Status\StatusResponse;

class ResponseHelperTest extends TestCase
{

    public function testItDetectsAnErrorRequestFromTheSerializedObject()
    {
        $response = new ErrorResponse(new Error(123, '123'));
        $encodedResponse = json_encode($response);

        $this->assertTrue(ResponseHelper::isAnError($encodedResponse));


        $response = new StatusResponse(new Status(123, 'ok'), '123');
        $encodedResponse = json_encode($response);

        $this->assertFalse(ResponseHelper::isAnError($encodedResponse));
    }

    public function testAssociativeArrayIsRecognized()
    {

        $array = [
            'hello' => 'value',
            'key' => 'value',
        ];

        $this->assertTrue(ResponseHelper::isAssociativeArray($array));
    }

    public function testMixedArrayIsNotRecognizedAsAssociative()
    {

        $array = [
            'zero' => 'value',
            0 => 'value',
            'key' => 'value',
        ];

        $this->assertFalse(ResponseHelper::isAssociativeArray($array));
    }

    public function testIndexArrayIsNotRecognizedAsAssociative()
    {

        $array = [
            'value1',
            'value2',
        ];

        $this->assertFalse(ResponseHelper::isAssociativeArray($array));
    }
    
    public function testNullAndEmptyAreNotRecognizedAsAssociative()
    {
        $this->assertFalse(ResponseHelper::isAssociativeArray(null));
        $this->assertFalse(ResponseHelper::isAssociativeArray([]));
    }
}