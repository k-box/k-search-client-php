<?php
namespace Tests\Integration;

use Tests\TestCase;
use KSearchClient\Client;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Search\SearchParams;
use KSearchClient\Http\Authentication;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Exception\InvalidDataException;
use KSearchClient\Exception\SerializationException;
use Tests\Concern\SetupIntegrationTest;
use KSearchClient\Model\Search\SearchResults;

class SearchDataTest extends TestCase
{
    use SetupIntegrationTest;

    public function testSearchWithNullFacetsAndNullFilters()
    {
        $params = tap(new SearchParams(), function($searchParams){
            $searchParams->search = '*'; // all indexed data
            $searchParams->filters = null;
            $searchParams->aggregations = null;
            $searchParams->limit = 1;
            $searchParams->offset = 0;
        });

        $response = $this->client->search($params);

        $this->assertInstanceOf(SearchResults::class, $response);
        $this->assertEquals('*', $response->query->search);
        $this->assertEquals(1, $response->query->limit);
        $this->assertEquals(0, $response->query->offset);
        $this->assertEmpty($response->query->filters);
        $this->assertEmpty($response->query->aggregations);
        
        $this->assertNotNull($response->totalMatches);
        $this->assertNotNull($response->items);
        $this->assertTrue($response->totalMatches >= 0);
        $this->assertEmpty($response->aggregations);
        $this->assertContainsOnlyInstancesOf(Data::class, $response->items);
        $this->assertTrue(count($response->items) <= 1);
    }

    public function testSearchWithEmptyFacetsAndEmptyFilters()
    {
        $params = tap(new SearchParams(), function($searchParams){
            $searchParams->search = '*'; // all indexed data
            $searchParams->filters = '';
            $searchParams->aggregations = [];
            $searchParams->limit = 1;
            $searchParams->offset = 0;
        });

        $response = $this->client->search($params);

        $this->assertInstanceOf(SearchResults::class, $response);
        $this->assertEquals('*', $response->query->search);
        $this->assertEquals(1, $response->query->limit);
        $this->assertEquals(0, $response->query->offset);
        $this->assertEmpty($response->query->filters);
        $this->assertEmpty($response->query->aggregations);
        
        $this->assertNotNull($response->totalMatches);
        $this->assertNotNull($response->items);
        $this->assertTrue($response->totalMatches >= 0);
        $this->assertEmpty($response->aggregations);
        $this->assertContainsOnlyInstancesOf(Data::class, $response->items);
        $this->assertTrue(count($response->items) <= 1);
    }

    public function testSearchWithEmptyStringFacetsAndEmptyArrayFilters()
    {
        $params = tap(new SearchParams(), function($searchParams){
            $searchParams->search = '*'; // all indexed data
            $searchParams->filters = [];
            $searchParams->aggregations = '';
            $searchParams->limit = 1;
            $searchParams->offset = 0;
        });

        $this->expectException(SerializationException::class);

        $response = $this->client->search($params);

    }
}