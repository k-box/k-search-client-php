[![build status](https://git.klink.asia/main/k-search-client-php/badges/master/build.svg)](https://git.klink.asia/main/k-search-client-php/commits/master) 
[![coverage report](https://git.klink.asia/main/k-search-client-php/badges/master/coverage.svg)](https://git.klink.asia/main/k-search-client-php/commits/master)

# K-Search PHP Client

> **This client is a complete rewrite and breaks the compatibility with the older versions**

The K-Search Client is a library that enables to communicate to a K-Serch instance.

For release changelogs see the [Changelog](./changelog.md)


### Requirements

- PHP 5.6 or above

Tested on PHP 5.6, 7.0 and 7.1. Runs on Windows, MacOS and Ubuntu 14.04+

**This release supports K-Core API version 2.1 and 2.2**. When configuring a K-Core connection please specify the API 
version, otherwise version 2.2 will be assumed.

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

Now you could require the client

```bash
composer require --prefer-dist php-http/guzzle6-adapter klink/adapterboilerplate@dev-ksearchv3
```

### Usage

_to be written_


## Contributing

Hey, we're accepting Pull Requests, please see our [contribution guide](./CONTRIBUTING.md) for more information.

## License

This project is licensed under the AGPL v3 license, see [LICENSE.txt](./LICENSE.txt).


