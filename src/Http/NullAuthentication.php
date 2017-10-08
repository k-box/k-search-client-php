<?php

namespace KSearchClient\Http;

use Http\Message\Authentication as AuthenticationContract;
use Psr\Http\Message\RequestInterface;

/**
 * Authenticate a PSR-7 Request using the K-Search Authentication.
 *
 */
final class NullAuthentication implements AuthenticationContract
{

    /**
     * {@inheritdoc}
     */
    public function authenticate(RequestInterface $request)
    {
        return $request;
    }
}
