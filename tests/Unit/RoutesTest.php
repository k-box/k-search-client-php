<?php
namespace Tests\Unit;

use Tests\TestCase;
use KSearchClient\Http\Routes;

class RoutesTest extends TestCase
{
    public function testRoutesFiltersTheBaseURL()
    {
        $routes = new Routes('http://www.google.com///////////');
        $this->assertStringStartsNotWith('http://www.google.com//', $routes->getDataAdd());

        $routes = new Routes(' http://www.google.com///////////  ');
        $this->assertStringStartsNotWith(' ', $routes->getDataAdd());
        $this->assertStringEndsNotWith('///////////  ', $routes->getDataAdd());

        $routes = new Routes(' http://www.google.com');
        $this->assertStringStartsWith('http://www.google.com/', $routes->getDataAdd());
    }


    public function testDataAddRouteIsReturned()
    {
        $routes = new Routes('http://localhost/');

        $route_url = $routes->getDataAdd();
        
        $this->assertEquals('http://localhost/api/0.0/data.add', $route_url);
    }

    public function testDataGetRouteIsReturned()
    {
        $routes = new Routes('http://localhost/');

        $route_url = $routes->getDataGet();
        
        $this->assertEquals('http://localhost/api/0.0/data.get', $route_url);
    }

    public function testDataDeleteRouteIsReturned()
    {
        $routes = new Routes('http://localhost/');

        $route_url = $routes->getDataDelete();
        
        $this->assertEquals('http://localhost/api/0.0/data.delete', $route_url);
    }

    public function testDataStatusRouteIsReturned()
    {
        $routes = new Routes('http://localhost/');

        $route_url = $routes->getDataStatus();
        
        $this->assertEquals('http://localhost/api/0.0/data.status', $route_url);
    }

    public function testSearchRouteIsReturned()
    {
        $routes = new Routes('http://localhost/');

        $route_url = $routes->getSearchQuery();
        
        $this->assertEquals('http://localhost/api/0.0/data.search', $route_url);
    }


}