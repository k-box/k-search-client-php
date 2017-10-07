<?php

namespace KSearchClient\Exception;

class InvalidDataException extends ErrorResponseException
{
    /**
     * Creates an InvalidDataException.
     * 
     * @param array $data The array that contains the description of what properties are invalid
     * @return InvalidDataException
     */
    public function __construct($data)
    {
        parent::__construct(sprintf('The Data object contains invalid attributes: %1$s', join(', ', array_keys($data))), 400, $data);
    }
}