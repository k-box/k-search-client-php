<?php

namespace KSearchClient\Tests\API;

use KSearchClient\API\ResponseHelper;
use KSearchClient\Model\Error\Error;
use KSearchClient\Model\Error\ErrorResponse;
use KSearchClient\Model\Status\Status;
use KSearchClient\Model\Status\StatusResponse;
use PHPUnit\Framework\TestCase;

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
}