<?php

namespace KSearchClient\Exception;

class ErrorResponseException extends \Exception
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * ErrorResponseException constructor.
     */
    public function __construct(string $message, int $code, $data)
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