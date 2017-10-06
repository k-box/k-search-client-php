<?php
namespace KSearchClient\Http;

class ResponseHelper
{
    public static function isAnError($jsonResponse)
    {
        $object = json_decode($jsonResponse);
        return isset($object->error);
    }
}