<?php

namespace KSearchClient;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Http\Client\HttpClient;
use Http\Client\Common\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use KSearchClient\Http\NullAuthentication;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Message\MessageFactory;
use JMS\Serializer\Serializer;
use KSearchClient\Http\ResponseHelper;
use KSearchClient\Http\Routes;
use KSearchClient\Exception\AuthTypeNotSupportedException;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Exception\InvalidDataException;
use KSearchClient\Model\Data\AddResponse;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\DataStatus;
use KSearchClient\Model\Data\DataStatusResponse;
use KSearchClient\Model\Data\GetResponse;
use KSearchClient\Model\Data\SearchParams;
use KSearchClient\Model\Data\SearchResponse;
use KSearchClient\Model\Data\SearchResults;
use KSearchClient\Model\Error\ErrorResponse;
use KSearchClient\Model\RPCRequest;
use KSearchClient\Model\Status\Status;
use KSearchClient\Model\Status\StatusResponse;
use KSearchClient\Validator\AuthTypeValidator;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Client
{
    const SERIALIZER_FORMAT = 'json';

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /** @var  Routes */
    private $routes;
    /**
     * @var Http\RequestFactory
     */
    private $apiRequestFactory;
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Create a client instance
     * 
     * @param string $kSearchUrl
     */
    public function __construct($kSearchUrl, Authentication $authentication, Http\RequestFactory $apiRequestFactory, Serializer $serializer, HttpClient $httpClient, MessageFactory $messageFactory)
    {
        // registering a PluginClient as the authentication should be appended to all requests
        $this->httpClient = new PluginClient(
            $httpClient ?: HttpClientDiscovery::find(),
            [
                new AuthenticationPlugin($authentication),
            ]
        );

        $this->messageFactory = $messageFactory ?: MessageFactoryDiscovery::find();
        $this->routes = new Routes($kSearchUrl);
        $this->apiRequestFactory = $apiRequestFactory;
        $this->serializer = $serializer;
    }

    /**
     * @param Data $data
     * @param string $dataTextualContents
     * @return Data
     */
    public function add(Data $data, $dataTextualContents = '')
    {
        $addRequest = $this->apiRequestFactory->buildDataAddRequest($data, $dataTextualContents);
        $route = $this->routes->getDataAdd();

        $response = $this->handleRequest($addRequest, $route);

        /** @var AddResponse $addResponse */
        $addResponse = $this->serializer->deserialize($response->getBody(), AddResponse::class, self::SERIALIZER_FORMAT);

        return $addResponse->result;
    }

    /**
     * Deletes a previously added Data
     * 
     * @param string $uuid The UUID of the data to delete
     * @return bool true if the data was deleted, false otherwise
     */
    public function delete($uuid)
    {
        $deleteRequest = $this->apiRequestFactory->buildDeleteRequest($uuid);
        $route = $this->routes->getDataDelete();

        $response = $this->handleRequest($deleteRequest, $route);

        /** @var StatusResponse $statusResponse */
        $statusResponse = $this->serializer->deserialize($response->getBody(), StatusResponse::class, self::SERIALIZER_FORMAT);
        return strtolower($statusResponse->result->status) === 'ok';
    }

    /**
     * @param string $uuid
     * @return Data
     */
    public function get($uuid)
    {
        $request = $this->apiRequestFactory->buildGetRequest($uuid);
        $route = $this->routes->getDataGet();

        $response = $this->handleRequest($request, $route);

        /** @var GetResponse $getRes */
        $getResponse = $this->serializer->deserialize($response->getBody(), GetResponse::class, self::SERIALIZER_FORMAT);
        return $getResponse->result;
    }

    /**
     * Get the processing status of a recently added data
     * 
     * @param string $uuid
     * @return string The status. Possible values are "ok", "queued"
     */
    public function getStatus($uuid)
    {
        $request = $this->apiRequestFactory->buildStatusRequest($uuid);
        $route = $this->routes->getDataStatus();

        $response = $this->handleRequest($request, $route);

        /** @var DataStatusResponse $getRes */
        $dataStatusResponse = $this->serializer->deserialize($response->getBody(), DataStatusResponse::class, self::SERIALIZER_FORMAT);
        // todo: check for deserialization errors and in case status is null raise an exception
        return $dataStatusResponse->result->status;
    }

    /**
     * @return SearchResults
     */
    public function search(SearchParams $searchParams)
    {
        $request = $this->apiRequestFactory->buildSearchRequest($searchParams);
        $route = $this->routes->getSearchQuery();

        $response = $this->handleRequest($request, $route);


        /** @var SearchResponse $searchResponse */
        $searchResponse = $this->serializer->deserialize($response->getBody(), SearchResponse::class, self::SERIALIZER_FORMAT);
        return $searchResponse->result;
    }

    /**
     * @param ResponseInterface $response
     * @throws ErrorResponseException
     */
    private function checkResponseError(ResponseInterface $response)
    {
        $responseBody = $response->getBody();

        if($response->getStatusCode() !== 200){
            print (string)$responseBody;
            throw new ErrorResponseException(!empty($response->getReasonPhrase()) ? $response->getReasonPhrase() : 'There was a problem in fulfilling your request.', $response->getStatusCode(), $responseBody);
        }

        if (ResponseHelper::isAnError($responseBody)) {
            /** @var ErrorResponse $errorResponse */
            $errorResponse = $this->serializer->deserialize($responseBody, ErrorResponse::class, self::SERIALIZER_FORMAT);
            
            if($errorResponse->error->code === 400){
                throw new InvalidDataException($errorResponse->error->data);
            }

            throw new ErrorResponseException($errorResponse->error->message, $errorResponse->error->code, $errorResponse->error->data);
        }
    }

    /**
     * @param $request
     * @param $route
     * @return ResponseInterface
     */
    private function handleRequest($request, $route)
    {
        $serializedRequestBody = $this->serializer->serialize($request, self::SERIALIZER_FORMAT);
        $request = $this->messageFactory->createRequest('POST', $route, [], $serializedRequestBody);

        $response = $this->httpClient->sendRequest($request);
        $this->checkResponseError($response);

        return $response;
    }

    /**
     * Build a K-Search client
     * 
     * @param string $instanceUrl The K-Search instance URL
     * @param \KSearchClient\Http\Authentication $authentication The authentication credentials, if necessary
     * @return Client
     */
    public static function build($instanceUrl, Authentication $authentication = null)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $factory = new Http\RequestFactory;
        $serializer = \JMS\Serializer\SerializerBuilder::create()
            ->build();
        $httpClient = HttpClientDiscovery::find();
        $messageFactory = MessageFactoryDiscovery::find();

        return new self($instanceUrl, $authentication ? $authentication : (new NullAuthentication), $factory, $serializer, $httpClient, $messageFactory);
    }
}