<?php
namespace Tests\Integration;

use Tests\TestCase;
use KSearchClient\Client;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Search\SearchParams;
use KSearchClient\Model\Search\Aggregations;
use KSearchClient\Model\Search\Aggregation;
use KSearchClient\Http\Authentication;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Ramsey\Uuid\Uuid;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Exception\InvalidDataException;
use KSearchClient\Exception\SerializationException;
use Tests\Concern\SetupIntegrationTest;
use KSearchClient\Model\Search\SearchResults;
use KSearchClient\Model\Search\AggregationResult;
use KSearchClient\Model\Search\SortParam;

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


    public function test_search_returns_aggregations()
    {
        $this->addTestDummyData();

        $aggregations = [
            Aggregations::MIME_TYPE => new Aggregation()
        ];

        $params = tap(new SearchParams(), function($searchParams) use($aggregations) {
            $searchParams->search = '*'; // all indexed data
            $searchParams->filters = null;
            $searchParams->aggregations = $aggregations;
            $searchParams->offset = 0;
        });

        $response = $this->client->search($params);

        $this->assertInstanceOf(SearchResults::class, $response);
        $this->assertEquals('*', $response->query->search);
        $this->assertNotEmpty($response->query->aggregations);
        
        $this->assertNotNull($response->totalMatches);
        $this->assertNotNull($response->items);
        $this->assertTrue($response->totalMatches >= 0);
        $this->assertContainsOnlyInstancesOf(Data::class, $response->items);
        $this->assertNotEmpty($response->aggregations);
        
        $aggregationResult = $response->aggregations;
        
        $this->assertEquals([Aggregations::MIME_TYPE], array_keys($aggregationResult));
        
        $this->assertContainsOnlyInstancesOf(AggregationResult::class, $aggregationResult[Aggregations::MIME_TYPE]);
        $this->assertCount(1, $aggregationResult[Aggregations::MIME_TYPE]);
        $this->assertNotEmpty($aggregationResult[Aggregations::MIME_TYPE][0]->value);
        $this->assertNotEmpty($aggregationResult[Aggregations::MIME_TYPE][0]->count);
    }


    public function test_search_returns_sorted_entities()
    {
        $this->clearIndexedDocuments();
        $this->addSortableTestDummyData();

        
        $sortParam = new SortParam;
        $sortParam->field = SortParam::PROPERTIES_UPDATED_AT;
        $sortParam->order = SortParam::ORDER_ASC;

        $params = tap(new SearchParams(), function($searchParams) use($sortParam) {
            $searchParams->search = '*'; // all indexed data
            $searchParams->filters = null;
            $searchParams->sort = [$sortParam];
            $searchParams->offset = 0;
            $searchParams->limit = 50;
        });

        $response = $this->client->search($params);

        $this->assertInstanceOf(SearchResults::class, $response);
        $this->assertEquals('*', $response->query->search);
        $this->assertEquals([$sortParam], $response->query->sort);
        
        $this->assertNotNull($response->totalMatches);
        $this->assertNotNull($response->items);
        $this->assertTrue(count($response->items) >= 2, "Items must be 2 or more");

        $updateDates = array_map(function($i){
            return $i->properties->updated_at->format('Y-m-d H:i:s');
        }, $response->items);
        
        $first = $updateDates[0];
        $last = $updateDates[count($updateDates)-1];

        $this->assertEquals('2008-07-28 15:47:31', $first);
        $this->assertEquals('2008-07-29 15:47:31', $last);
    }


    private function addTestDummyData()
    {
        $uuid = Uuid::uuid4()->toString();

        $to_add = $this->createDataModel($uuid);

        $added_data = $this->client->add($to_add, 'textual content to use');
    }

    private function addSortableTestDummyData()
    {
        $uuid = Uuid::uuid4()->toString();

        $to_add = $this->createDataModel($uuid);

        $to_add->properties->updated_at = new \DateTime('2008-07-28T15:47:31Z', new \DateTimeZone('UTC'));

        $added_data = $this->client->add($to_add, 'textual content to use');
    
        $uuid = Uuid::uuid4()->toString();

        $to_add = $this->createDataModel($uuid);

        $to_add->properties->updated_at = new \DateTime('2008-07-29T15:47:31Z', new \DateTimeZone('UTC'));

        $added_data = $this->client->add($to_add, 'textual content to use');
    }
}