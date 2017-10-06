<?php

namespace KSearchClient\Http;

use Http\Message\Authentication as AuthenticationContract;
use Psr\Http\Message\RequestInterface;

/**
 * Authenticate a PSR-7 Request using the K-Search Authentication.
 *
 */
final class Authentication implements AuthenticationContract
{
    /** 
     * @var string
     */
    private $app_secret;
    
    /** 
     * @var string
     */
    private $app_url;

    /**
     * @param string $app_secret
     * @param string $app_url
     */
    public function __construct($app_secret, $app_url)
    {
        $this->app_secret = $app_secret;
        $this->app_url = $app_url;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(RequestInterface $request)
    {
        $authorization_header = sprintf('Bearer %s', $this->app_secret);

        return $request
                ->withHeader('Authorization', $authorization_header)
                ->withHeader('Origin', $this->app_url);
    }
}
