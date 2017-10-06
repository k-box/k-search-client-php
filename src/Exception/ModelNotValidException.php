<?php
namespace KSearchClient\Exception;


class ModelNotValidException extends KSearchClientException
{

    /**
     * @var array
     */
    private $errors;

    public function __construct(array $errors)
    {
        parent::__construct('The model is not valid. Call getErrors() in the exception for more info.');
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}