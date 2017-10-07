<?php
namespace Tests;

use KSearchClient\Http\Routes;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use Concern\GeneratesData;
}