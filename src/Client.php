<?php

namespace KSearchClient;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\MessageFactory;
use JMS\Serializer\Serializer;
use KSearchClient\API\ResponseHelper;
use KSearchClient\Exception\AuthTypeNotSupportedException;
use KSearchClient\Exception\ErrorResponseException;
use KSearchClient\Model\Data\AddRequest;
use KSearchClient\Model\Data\AddResponse;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Error\ErrorResponse;
use KSearchClient\Validator\AuthTypeValidator;

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

    public function __construct(Authentication $authentication, string $kSearchUrl, API\RequestFactory $apiRequestFactory, Serializer $serializer, HttpClient $httpClient = null, MessageFactory $messageFactory = null)
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

    public function addData(Data $data, $dataTextualContents = '')
    {
        $addRequest = $this->apiRequestFactory->buildDataAddRequest($data, $dataTextualContents);
        $serializedRequestBody = $this->serializer->serialize($addRequest, self::SERIALIZER_FORMAT);

        $request = $this->messageFactory->createRequest( 'POST', $this->routes->getDataAdd(), [], $serializedRequestBody);
        $this->authentication->authenticate($request);

        $response = $this->httpClient->sendRequest($request);
        $responseBody = $response->getBody();

        if (ResponseHelper::isAnError($responseBody)) {
            /** @var ErrorResponse $errorResponse */
            $errorResponse = $this->serializer->deserialize($responseBody, ErrorResponse::class, self::SERIALIZER_FORMAT);
            throw new ErrorResponseException($errorResponse->error->message, $errorResponse->error->code, $errorResponse->error->data);
        }

        return $this->serializer->deserialize($response->getBody(), AddResponse::class, self::SERIALIZER_FORMAT);
    }
}