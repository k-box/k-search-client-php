[![build status](https://git.klink.asia/main/k-search-client-php/badges/master/build.svg)](https://git.klink.asia/main/k-search-client-php/commits/master) 
[![coverage report](https://git.klink.asia/main/k-search-client-php/badges/master/coverage.svg)](https://git.klink.asia/main/k-search-client-php/commits/master)

# K-Search PHP Client

> **This client is a complete rewrite and breaks the compatibility with the older versions**
> **It targets K-Search API version 3.0**.

The K-Search Client is a library that enables to communicate to a K-Serch instance.

For release changelogs see the [Changelog](./changelog.md)

Compatible and tested on PHP 5.6, 7.0 and 7.1.

## Getting Started

### Installation

The K-Search client uses [Composer](http://getcomposer.org/) to manage its dependencies. 
So, before using the Boilerplate, make sure you have Composer installed on your machine.

In order to require it in your project add the following repository configuration to your `composer.json` file.

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://git.klink.asia/main/k-search-client-php"
    }
]
```

The K-Search api client is not hard coupled to [Guzzle](https://github.com/guzzle/guzzle) or any other library that sends HTTP messages. It uses an abstraction called [HTTPlug](http://httplug.io/). This will give you the flexibilty to choose what PSR-7 implementation and HTTP client to use.

If you just want to get started quickly you should run the following command:

```bash
composer require php-http/guzzle6-adapter guzzlehttp/psr7 klink/adapterboilerplate@dev-ksearchv3
```

### Why requiring so many packages?

K-Search client has a dependency on the virtual package
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) which requires to you install **an** adapter, but we do not care which one. That is an implementation detail in your application. We also need **a** PSR-7 implementation and **a** message factory. 

You do not have to use the `php-http/guzzle6-adapter` if you do not want to. You may use the `php-http/curl-client`. Read more about the virtual packages, why this is a good idea and about the flexibility it brings at the [HTTPlug docs](http://docs.php-http.org/en/latest/httplug/users.html).

### Usage

You should always use Composer's autoloader in your application to automatically load the your dependencies. 

All examples below assumes you've already included this in your file:

```php
require 'vendor/autoload.php';
use KSearchClient\Client;
```

_to be written_


## Contributing

Hey, we're accepting Pull Requests, please see our [contribution guide](./CONTRIBUTING.md) for more information.

## License

This project is licensed under the AGPL v3 license, see [LICENSE.txt](./LICENSE.txt).


