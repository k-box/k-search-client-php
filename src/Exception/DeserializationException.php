<?php
namespace KSearchClient\Exception;


class DeserializationException extends KSearchClientException
{
    /**
     * 
     * @param string $message
     * @param string $json the original JSON that raised the deserialization error
     */
    public function __construct($message, $json)
    {
        parent::__construct("$message. $json");
    }

}