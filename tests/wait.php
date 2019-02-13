<?php

require(__DIR__ . '/../vendor/autoload.php');

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Client\Exception\RequestException;

$messageFactory = MessageFactoryDiscovery::find();
$httpClient = HttpClientDiscovery::find();

$route = 'http://127.0.0.1:8080/api/3.6/data.get';

$body = '{"params": {"uuid": "cc1bbc0b-20e8-4e1f-b894-fb067e81c5dd"},"id":"request-3d254173"}';

$request = $messageFactory->createRequest('POST', $route, [], $body);

$start = time();

while (true) {
    try {
        $response = $httpClient->sendRequest($request);

        if( $response->getStatusCode() === 200 ){
            fwrite(STDOUT, 'Docker container started!'.PHP_EOL);
            exit(0);
        }
    } catch (RequestException $exception) {
        $elapsed = time() - $start;

        if ($elapsed > 30) {
            fwrite(STDERR, 'Docker container did not start in time...'.PHP_EOL);
            exit(1);
        }

        fwrite(STDOUT, 'Waiting for container to start...'.PHP_EOL);
        sleep(2);
    }
}