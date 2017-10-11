<?php
namespace KSearchClient\Exception;


class SerializationException extends KSearchClientException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

}