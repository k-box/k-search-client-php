<?php
namespace Tests;

use KSearchClient\Http\Routes;

class RoutesTest extends TestCase
{
    public function testItFiltersTheBaseURL()
    {
        $routes = new Routes('http://www.google.com///////////');
        $this->assertStringStartsNotWith('http://www.google.com//', $routes->getDataAdd());

        $routes = new Routes(' http://www.google.com///////////');
        $this->assertStringStartsNotWith(' ', $routes->getDataAdd());

        $routes = new Routes(' http://www.google.com');
        $this->assertStringStartsWith('http://www.google.com/', $routes->getDataAdd());
    }
}