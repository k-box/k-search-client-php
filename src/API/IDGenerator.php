<?php
namespace KSearchClient\API;

class IDGenerator
{
    public function getNewId()
    {
        return uniqid();
    }
}