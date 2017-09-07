<?php
namespace KSearchClient\API;

class ResponseHelper
{
    public static function isAnError($jsonResponse)
    {
        $object = json_decode($jsonResponse);
        return isset($object->error);
    }
}