<?php

namespace KSearchClient;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory;
use JMS\Serializer\Serializer;
use KSearchClient\API\ResponseHelper;
use KSearchClient\API\Routes;
use KSearchClient\Exception\AuthTypeNotSupportedException;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Exception\ModelNotValidException;
use KSearchClient\Model\Data\AddResponse;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\DataStatus;
use KSearchClient\Model\Data\DataStatusResponse;
use KSearchClient\Model\Data\GetResponse;
use KSearchClient\Model\Data\SearchParams;
use KSearchClient\Model\Data\SearchResponse;
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

    /**
     * @var Authentication
     */
    private $authentication;

    /** @var  Routes */
    private $routes;
    /**
     * @var API\RequestFactory
     */
    private $apiRequestFactory;
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Authentication $authentication, string $kSearchUrl, API\RequestFactory $apiRequestFactory, Serializer $serializer, HttpClient $httpClient, MessageFactory $messageFactory)
    {
        $this->authentication = $authentication;

        if (!AuthTypeValidator::isSupported($authentication)) {
            throw new AuthTypeNotSupportedException('KSearch API supports the following types of authentication: Bearer and BasicAuth.');
        }

        $this->httpClient = $httpClient ?: HttpClientDiscovery::find();
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
    public function addData(Data $data, string $dataTextualContents = '')
    {
        $addRequest = $this->apiRequestFactory->buildDataAddRequest($data, $dataTextualContents);
        $route = $this->routes->getDataAdd();

        $response = $this->handleRequest($addRequest, $route);

        /** @var AddResponse $addResponse */
        $addResponse = $this->serializer->deserialize($response->getBody(), AddResponse::class, self::SERIALIZER_FORMAT);

        return $addResponse->result;
    }

    /**
     * @param $uuid
     * @return Status
     */
    public function deleteData(string $uuid): Status
    {
        $deleteRequest = $this->apiRequestFactory->buildDeleteRequest($uuid);
        $route = $this->routes->getDataDelete();

        $response = $this->handleRequest($deleteRequest, $route);

        /** @var StatusResponse $statusResponse */
        $statusResponse = $this->serializer->deserialize($response->getBody(), StatusResponse::class, self::SERIALIZER_FORMAT);
        return $statusResponse->result;
    }

    /**
     * @param $uuid
     * @return Data
     */
    public function getData(string $uuid): Data
    {
        $request = $this->apiRequestFactory->buildGetRequest($uuid);
        $route = $this->routes->getDataGet();

        $response = $this->handleRequest($request, $route);

        /** @var GetResponse $getRes */
        $getResponse = $this->serializer->deserialize($response->getBody(), GetResponse::class, self::SERIALIZER_FORMAT);
        return $getResponse->result;
    }

    public function getDataStatus(string $uuid): DataStatus
    {
        $request = $this->apiRequestFactory->buildStatusRequest($uuid);
        $route = $this->routes->getDataGet();

        $response = $this->handleRequest($request, $route);

        /** @var DataStatusResponse $getRes */
        $dataStatusResponse = $this->serializer->deserialize($response->getBody(), DataStatusResponse::class, self::SERIALIZER_FORMAT);
        return $dataStatusResponse->result;
    }

    public function searchData(SearchParams $searchParams): Data
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

        if (ResponseHelper::isAnError($responseBody)) {
            /** @var ErrorResponse $errorResponse */
            $errorResponse = $this->serializer->deserialize($responseBody, ErrorResponse::class, self::SERIALIZER_FORMAT);
            throw new ErrorResponseException($errorResponse->error->message, $errorResponse->error->code, $errorResponse->error->data);
        }
    }

    /**
     * @param $request
     * @param $route
     * @return ResponseInterface
     */
    private function handleRequest($request, $route): ResponseInterface
    {
        $serializedRequestBody = $this->serializer->serialize($request, self::SERIALIZER_FORMAT);
        $request = $this->messageFactory->createRequest('POST', $route, [], $serializedRequestBody);

        $response = $this->httpClient->sendRequest($request);
        $this->checkResponseError($response);

        return $response;
    }

    /**
     * @param Authentication $authentication
     * @param $kSearchUrl
     * @return Client
     */
    public static function buildDefault(Authentication $authentication, $kSearchUrl)
    {
        AnnotationRegistry::registerLoader('class_exists');

        $factory = API\RequestFactory::buildDefault();
        $serializer = \JMS\Serializer\SerializerBuilder::create()
            ->build();
        $httpClient = HttpClientDiscovery::find();
        $messageFactory = MessageFactoryDiscovery::find();

        return new self($authentication, $kSearchUrl, $factory, $serializer, $httpClient, $messageFactory);
    }
}