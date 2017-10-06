<?php

namespace KSearchClient\Exception;

class ErrorResponseException extends KSearchClientException
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * ErrorResponseException constructor.
     * 
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code, $data)
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}