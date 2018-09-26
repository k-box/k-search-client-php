<?php
namespace Tests\Unit;

use Tests\TestCase;
use KSearchClient\Model\Data\GeographicGeometry;

class GeographicGeometryTest extends TestCase
{
    public function test_point_geometry_creation()
    {
        $geometry = GeographicGeometry::point(60.01621,20.57422);

        $this->assertEquals('Point', $geometry->type);
        $this->assertEquals([60.01621,20.57422], $geometry->coordinates);
        $this->assertEquals('{"type": "Point", "coordinates": [60.01621,20.57422]}', $geometry->__toString());
    }
    
    public function test_polygon_geometry_creation()
    {
        $coordinates = [
            [100,0],
            [101,0],
            [101,1],
            [100,1],
            [100,0]
        ];
        $geometry = GeographicGeometry::polygon($coordinates);

        $this->assertEquals('Polygon', $geometry->type);
        $this->assertEquals($coordinates, $geometry->coordinates);
        $this->assertEquals('{"type": "Polygon", "coordinates": [[[100,0],[101,0],[101,1],[100,1],[100,0]]]}', $geometry->__toString());
    }


    
}