[![Build Status](https://travis-ci.org/k-box/k-search-client-php.svg?branch=master)](https://travis-ci.org/k-box/k-search-client-php)

# K-Search PHP Client

The K-Search Client is a library that abstract the communication to a [K-Search](https://github.com/k-box/k-search) instance.

The library enables the following operations

- Perform [add Data requests](#adding-data)
- Get [status of add data requests](#monitoring-the-status-of-the-data-add-request)
- [Retrieve data details](#get-data)
- [Remove an already added data](#remove-data)
- [Search data](#search-data) using terms, filters and aggregations.

**Compatible with K-Search version 3.x**.

For release changelogs see the [Changelog](./changelog.md)

> The client always ask for the most recent version of the API. If you have an older K-Search version or you want to use an old API version, please specify it when [instantiating the client](#instantiate-a-client)

**Requirements**

- [PHP 5.6](http://www.php.net/) or above.

## Getting Started

### Installation

The K-Search client uses [Composer](http://getcomposer.org/) to manage its dependencies. 
So, before using the Client, make sure you have Composer installed on your machine.

In order to require it in your project add the following repository configuration to your `composer.json` file.

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/k-box/k-search-client-php"
    }
]
```

The K-Search api client is not hard coupled to [Guzzle](https://github.com/guzzle/guzzle) or any other library that sends HTTP messages. It uses an abstraction called [HTTPlug](http://httplug.io/). This will give you the flexibilty to choose what PSR-7 implementation and HTTP client to use.

If you just want to get started quickly you should run the following command:

```bash
composer require php-http/guzzle6-adapter guzzlehttp/psr7 k-box/k-search-client-php:3.0.*
```

### Why requiring so many packages?

K-Search client has a dependency on the virtual package
[php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation) which requires to you install **an** adapter, but we do not care which one. That is an implementation detail in your application. We also need **a** PSR-7 implementation and **a** message factory. 

You do not have to use the `php-http/guzzle6-adapter` if you do not want to. You may use the `php-http/curl-client`. Read more about the virtual packages, why this is a good idea and about the flexibility it brings at the [HTTPlug docs](http://docs.php-http.org/en/latest/httplug/users.html).

### Usage

You should always use Composer's autoloader in your application to automatically load the dependencies. 

All examples below assumes you've already included this in your file:

```php
require 'vendor/autoload.php';
use KSearchClient\Client;
use KSearchClient\Http\Authentication;
```

#### Instantiate a client

**When no authentication is required**

The client needs a valid URL of a K-Search instance, e.g. https://search.klink.asia/. After obtaining the URL you can instantiate a client like

```php
use KSearchClient\Client;

// URL of the K-Search instance you want to connect to
$service_url = 'https://search.klink.asia/';

// Generate the client
$client = Client::build($service_url);
```

**When authentication is required**

The client, in addition to the K-Search URL, needs a valid `app_secret` and `app_url` from the K-Registry that handles application registration for the specific K-Search instance. After obtaining the pair you can instantiate a client like

```php
use KSearchClient\Client;
use Http\Message\Authentication;

// Authentication
$app_secret = 'Som3RandomW0rds';
$app_url = 'http://localhost:8080/';

// URL of the K-Search instance you want to connect to
$service_url = 'https://search.klink.asia/';

// Generate the client
$client = Client::build($service_url, new Authentication($app_secret, $app_url));
```

**Wanting different API versions**

Forcing an API version usage is possible while creating a Client instance.
Specify the API version as the last argument of the `Client::build()` method.

```php
use KSearchClient\Client;
use Http\Message\Authentication;

$app_secret = 'Som3RandomW0rds';
$app_url = 'http://localhost:8080/';
$service_url = 'https://search.klink.asia/';

$client = Client::build($service_url, new Authentication($app_secret, $app_url), '3.4');
// => a client for the API version 3.4 will be returned
```

#### Working with a Client instance

In this section an example usage for each library feature is presented.

All examples below assumes you've already [instantiated a Client](#instantiate-a-client) using an instantiation approach and that the Client is accessible using a `$client` variable in the current scope.

##### Adding data

Adding Data to the K-Search means creating a description of the data to be added and indicating the way the K-Search should obtain the real content of the described data.

The K-Search supports different data description type, the most common common are `document` and `video`. Depending on the data type a set of different properties is expected. `document` refers to a generic textual document, while `video` is designed to describe a video file.

For more information on data types and the supported formats refer to the [K-Search documentation](https://github.com/k-box/k-search).

**Creating a data descriptor**

A Data descriptor for a document can be instantiated like

```php
use DateTime;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\Copyright;
use KSearchClient\Model\Data\CopyrightOwner;
use KSearchClient\Model\Data\CopyrightUsage;
use KSearchClient\Model\Data\Properties;
use KSearchClient\Model\Data\Uploader;
use KSearchClient\Model\Data\Author;

//Create the Data object that will contain all the properties
// if not specified all fields are required
$data = new Data();

//The UUID that identifies this data, as string
$data->uuid = 'b2c16bd1-6739-4fd9-a1e2-7dde785bed54';

//The SHA-2 hash of the content that is described by this Data instance
$data->hash = hash('sha512', 'File Content'); 

//The type of the data descriptor. 'video' or 'document'
$data->type = 'document'; 

//The URL at which the described data can be downloaded. 
// It must return the exact content, no preview pages or other screens
$data->url = 'http://norvig.com/palindrome.html';

// The data properties. Those properties are dependent from the specified $data->type
$data->properties = new Properties(); //The document properties
$data->properties->title = 'Adventures of Sherlock Holmes';
$data->properties->filename = 'adventures-of-sherlock-holmes.pdf';
$data->properties->mime_type = 'application/pdf';
$data->properties->language = 'en'; // The ISO 639-1 language code
$data->properties->created_at = new DateTime();
$data->properties->updated_at = new DateTime();
$data->properties->size = 2048; //The size of the file content in bytes
$data->properties->abstract = 'It is a novel about a detective';
$data->properties->thumbnail = 'https://ichef.bbci.co.uk/news/660/cpsprodpb/153B4/production/_89046968_89046967.jpg';

$data->uploader = new Uploader(); //The originating source where the data has been uploaded or created.
$data->uploader->name = 'Uploader name';

$author = new Author(); 
$author->email = 'arthur@conan.doyle';
$author->name = 'Arthur Conan Doyle';
$author->contact = '221B Baker Street';

//The authors of the piece of data
$data->authors = [$author]; //An array with the different document's authors

$data->copyright = new Copyright(); //Copyright info
$data->copyright->owner = new CopyrightOwner();
$data->copyright->owner->name = 'KLink Organization';
$data->copyright->owner->email = 'info@klink.asia';
$data->copyright->owner->website = 'http://klink.asia';

$data->copyright->usage = new CopyrightUsage(); //Copyright license info
$data->copyright->usage->short = 'MPL-2.0'; // it must be a valid SPDX identifier https://spdx.org/licenses/
$data->copyright->usage->name = 'Mozilla Public License 2.0';
$data->copyright->usage->reference = 'https://spdx.org/licenses/MPL-2.0.html';
```

For a `video` data type, the `$data->properties->video` property is required.

```php
use KSearchClient\Model\Data\Properties\Video;
use KSearchClient\Model\Data\Properties\Streaming;
use KSearchClient\Model\Data\Properties\Source;
use KSearchClient\Model\Data\Properties\Audio;
use KSearchClient\Model\Data\Properties\Subtitles;

$data->properties->video = new Video();
$data->properties->video->duration = '10 min';

// information of the video source
$data->properties->video->source = new Source();
$data->properties->video->source->resolution = '1920x1080';
$data->properties->video->source->format = 'h264';
$data->properties->video->source->bitrate = '1Mbps';

$streaming = new Streaming();
$streaming->type = 'youtube'; //It can be youtube, dash or hls
$streaming->url = 'https://www.youtube.com/watch?v=iEueWyu0TXA';
$data->properties->video->streaming = [$streaming]; //A video can have multiple streaming 

$audioEn = new Audio();
$audioEn->language = 'en';
$audioEn->bitrate = '1 Mbps';
$audioEn->format = 'mp3';

$audioEs = new Audio();
$audioEs->language = 'es';
$audioEs->bitrate = '1 Mbps';
$audioEs->format = 'mp3';

$data->properties->audio = [
  $audioEn,
  $audioEs,
];

$subtitles = new Subtitles();
$subtitles->language = 'es';
$subtitles->file = 'http://opensubtitles.org/get/iEueWyu0TXA';
$subtitles->format = 'txt';
$data->properties->subtitles = [$subtitles];
```

There are two ways the K-Search can obtain the text/file content related to the data descriptor being added.

**Download using URL**

The K-Search is able to download the referenced data from the given `$data->url`. To let the K-Search download the file use

```php
$added_data = $client->add($data);
```

The progress of the add request can be, then, monitored using the [`getStatus($data->uuid)` method](#monitoring-the-status-of-the-data-add-request).


**Sending textual data**

If the file is not supported by the K-Search or you want to specify a different text representation of the file content, you can do it via the second parameter of the `add` call.

The string must be ascii or UTF-8 encoded.

```php
$added_data = $client->add($data, 'This text will be used for search retrieval');
```

When this approach is used, the data will be avaiable immediately in search results.

##### Monitoring the status of the data add request

Once an add request is sent, the developer must control its status:

- `ok`: Means that the data has been correctly proccessed by the K-Search
- `queued`: Means that the data is in the queue for being processed
- `error`: Means that an error occurred while processing the request

An example for checking the status is:

```php
$uuid = 'b2c16bd1-6739-4fd9-a1e2-7dde785bed54';
$status = $client->getStatus($uuid);
// instance of KSearchClient\Model\Data\DataStatus
```

In case of error, the `$status->message` field will contain a description 
of the occurred problem.

##### Get data

From the K-Search is possible to obtain data details given a known data UUID

```php
$uuid = 'b2c16bd1-6739-4fd9-a1e2-7dde785bed54';
$data = $client->get($uuid);
// instance of KSearchClient\Model\Data\Data
```

##### Remove data

Removing a data is performed by specifying the UUID of the data to remove.

```php
$uuid = 'b2c16bd1-6739-4fd9-a1e2-7dde785bed54';
$done = $client->delete($uuid);
// true || false
```

Even if the method returns a boolean you can safely ignore the return value, as in case of errors an exception will be thrown.

##### Search data

Search enables to use the full text retrieval capability of the K-Search to list data that matches a specific criteria.

Search criteria can be formulated using:

- _terms_: a string representing the keywords to find
- _filters_: the criteria used to select which documents needs to be searched for the terms

Of course, filters are not required.

```php
$searchParams = new SearchParams();
$searchParams->search = 'Sherlock';

$result = $client->search($searchParams);
// instance of KSearchClient\Model\Search\SearchResults
```

**Filters**

The filter option accepts a [Lucene query syntax](https://lucene.apache.org/core/2_9_4/queryparsersyntax.html)

```php
$searchParams = new SearchParams();
$searchParams->search = 'Sherlock';
$searchParams->filters = 'properties.language:en AND properties.mime_type:"application/pdf"';

$result = $client->search($searchParams);
// instance of KSearchClient\Model\Search\SearchResults
```
Currently the supported filter fields are defined in `KSearchClient\Model\Search\Filters`:

- `uuid`
- `type`
- `properties.language`
- `properties.created_at`
- `properties.updated_at`
- `properties.size`
- `properties.collections`
- `properties.tags`
- `properties.mime_type`
- `properties.owner.name`
- `properties.usage.short`
- `uploader.name`
- `uploader.organization`

Some filters accept free text terms, but most of them are bound to specific values. To know the possible values to use the `aggregation` concept was defined.

**Aggregations**

Aggregations consider all the possible values for a specific (supported) field and return the list of _N_ most common terms for the field.

For example if I want to know the 15 most common data languages

```php
use KSearchClient\Model\Search\Aggregation;
use KSearchClient\Model\Search\Aggregations;

$searchParams = new SearchParams();
$searchParams->search = 'Sherlock'; // this can be also * if no specific term should appear in the data content

$searchParams->aggregations = [];

$languageAggregation = new Aggregation();
$languageAggregation->countsFiltered = true;
$languageAggregation->limit = 15; // minimum 10, maximum 100
$languageAggregation->minCount = 1; // return aggregation values that have at least minCount matching entries

$searchParams->aggregations[Aggregations::LANGUAGE] = $languageAggregation;

$result = $client->search($searchParams);
// instance of KSearchClient\Model\Search\SearchResults
```

The `$languageAggregation->countsFiltered = true` (or `false`) will tell the K-Search to evaluate the aggregations after filters are applied. In this way aggregation refers only to the subset of documents that matched your filter criteria. Otherwise the aggregations are evaluated on the whole data added to the K-Search instance by any users.

The supported aggregations are defined in `KSearchClient\Model\Search\Aggregations`.

**Sorting**

By default search results are based on the score calculated for each data against the search query. Sometimes you might want to sort data differently.


```php
use KSearchClient\Model\Search\SortParam;

$searchParams = new SearchParams();
$searchParams->search = '*';


$sortParam = new SortParam;
$sortParam->field = SortParam::PROPERTIES_UPDATED_AT;
$sortParam->order = SortParam::ASC;

$searchParams->sort[] = [
    $sortParam
];

$result = $client->search($searchParams);
// instance of KSearchClient\Model\Search\SearchResults
```


## Testing

The code testing is automated using [PHPUnit](https://phpunit.de/).

There are 2 testing suites:

- `Unit`: test classes in isolation
- `Integration`: test the features using a real K-Search instance

The tests can be executed using

```bash
vendor/bin/phpunit
```

**Executing integration tests**

Integration tests requires to set the `KSEARCH_URL` environment variable to the URL of a running K-Search v3 instance.

Leaving the `KSEARCH_URL` variable empty will cause the integration tests to be skipped.

For specific tests a webserver that generates specific failures is needed. The Host and Port of that server can be configured with the `FAILURE_GENERATOR_SERVER` environment variable. The variable is expected to contain both host and port, like `docker.for.win.localhost:8001`, if the server is running on localhost port 8001 and the K-Search is running in a docker image on localhost.

The failure generator webserver replies with correct responses to HEAD requests, while generate a 404 for every GET request. An example implementation can be found in [github.com/k-box/http-failure-server](https://github.com/k-box/http-failure-server).

## Contributing

Hey, we're accepting Pull Requests, please see our [contribution guide](./CONTRIBUTING.md) for more information.

## License

This project is licensed under the AGPL v3 license, see [LICENSE.txt](./LICENSE.txt).
