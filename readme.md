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
use KSearchClient\Http\Authentication;
```

#### Instantiate a client

**When no authentication is required**

The client needs a valid URL of a K-Search instance, e.g. https://search.klink.asia/. After obtaining the URL you can instantiate a client like

```php
// URL of the K-Search instance you want to connect to
$service_url = 'https://search.klink.asia/';

// Generate the client
$client = Client::build($service_url);
```

**When authentication is required**

The client, in addition to the K-Search URL, needs a valid `app_secret` and `app_url` from the K-Registry. After obtaining the pair you can instantiate a client like

```php
// Authentication
$app_secret = 'Som3RandomW0rds';
$app_url = 'http://localhost:8080/';

// URL of the K-Search instance you want to connect to
$service_url = 'https://search.klink.asia/';

// Generate the client
$client = Client::build($service_url, new Authentication($app_secret, $app_url));
```

#### Working with documents

The client can do 4 operation with documents. Those are:

* To index a document/video
* To get the document's status
* To remove a document already indexed
* To retrieve a document from the index
* To search a document

##### Indexing a document

The client uses a class that represent a document. That class is `KSearchClient\Model\Data\Data`. In order to index a document, you have to create a new object of that class and fill the appropiate properties:

```php
require 'vendor/autoload.php';

use KSearchClient\Client;
use KSearchClient\Http\Authentication;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\Copyright;
use KSearchClient\Model\Data\CopyrightUsage;
use KSearchClient\Model\Data\Properties;
use KSearchClient\Model\Data\Uploader;
use KSearchClient\Model\Data\Author;

$client = Client::build($service_url, new Authentication($app_secret, $app_url));

//We create the Data object
$data = new Data(); 
$data->hash = hash('sha512', 'hash'); //The document hash
$data->type = 'document'; //This can be a 'video' or 'document'
$data->url = 'http://example.com/data.txt'; //The document URL
$data->uuid = 'b2c16bd1-6739-4fd9-a1e2-7dde785bed54'; //A unique UUID that identifies this document

$data->copyright = new Copyright(); //Copyright info
$data->copyright->owner = new CopyrightOwner();
$data->copyright->owner->name = 'KLink Organization';
$data->copyright->owner->email = 'info@klink.asia';
$data->copyright->owner->contact = 'KLink Website: http://www.klink.asia';

$data->copyright->usage = new CopyrightUsage(); //Copyright license info
$data->copyright->usage->short = 'MPL-2.0';
$data->copyright->usage->name = 'Mozilla Public License 2.0';
$data->copyright->usage->reference = 'https://spdx.org/licenses/MPL-2.0.html';

$data->properties = new Properties(); //The document properties
$data->properties->title = 'Adventures of Sherlock Holmes';
$data->properties->filename = 'adventures-of-sherlock-holmes.pdf';
$data->properties->mimeType = 'application/pdf';
$data->properties->language = 'en';
$data->properties->createdAt = new \DateTime();
$data->properties->updatedAt = new \DateTime();
$data->properties->size = 2048; //In bytes
$data->properties->abstract = 'It is a novel about a detective';
$data->properties->thumbnail = 'https://ichef.bbci.co.uk/news/660/cpsprodpb/153B4/production/_89046968_89046967.jpg';
$data->properties->tags = ['tag1', 'tag2']; //Custom tags associated to the data
$data->properties->collections = ['123', '456']; //Search data and browse within the hierarchy

$data->uploader = new Uploader(); //The originating source where the data has been uploaded or created.
$data->uploader->name = 'Uploader name';

$author = new Author(); //Author
$author->email = 'arthur@conan.doyle';
$author->name = 'Arthur Conan Doyle';
$author->contact = '221B Baker Street';

$data->author = [$author]; //An array with the different document's authors

//We index it
/** @var Data $responseData */
$responseData = $client->add($data);
```

The lines above are structured in 3 blocks. In the first block we create the `Client` object providing the authentication details.In the second block we create and fill the `Data` object with the document information. Finally, in the third block we just call to `$client->add($data)` that is responsible for authenticating in the system and indexing the data.

The `add` method returns a new `Data` object that will contain the same info than the `$data` variable.

##### Indexing a video

In that case are going to use the `Data` object again. However, we will need to fill more properties in the `Properties` object. The method in the client that indexes the video is also `add` and the response is the same as in thi above case.

```php
require 'vendor/autoload.php';

use KSearchClient\Client;
use KSearchClient\Http\Authentication;
use KSearchClient\Model\Data\Data;
use KSearchClient\Model\Data\Copyright;
use KSearchClient\Model\Data\CopyrightUsage;
use KSearchClient\Model\Data\Properties;
use KSearchClient\Model\Data\Properties\Video;
use KSearchClient\Model\Data\Properties\Streaming;
use KSearchClient\Model\Data\Properties\Source;
use KSearchClient\Model\Data\Properties\Audio;
use KSearchClient\Model\Data\Properties\Subtitles;
use KSearchClient\Model\Data\Uploader;
use KSearchClient\Model\Data\Author;

$client = Client::build($service_url, new Authentication($app_secret, $app_url));

//We create the Data object
$data = new Data(); 
$data->hash = hash('sha512', 'hash'); //The document hash
$data->type = 'video'; //This can be a 'video' or 'document'
$data->url = 'http://example.com/data.mp4'; //The document URL
$data->uuid = 'b2c16bd1-6739-4fd9-a1e2-7dde785bed54'; //A unique UUID that identifies this document

$data->copyright = new Copyright(); //Copyright info
$data->copyright->owner = new CopyrightOwner();
$data->copyright->owner->name = 'KLink Organization';
$data->copyright->owner->email = 'info@klink.asia';
$data->copyright->owner->contact = 'KLink Website: http://www.klink.asia';

$data->copyright->usage = new CopyrightUsage(); //Copyright license info
$data->copyright->usage->short = 'MPL-2.0';
$data->copyright->usage->name = 'Mozilla Public License 2.0';
$data->copyright->usage->reference = 'https://spdx.org/licenses/MPL-2.0.html';

$data->properties = new Properties(); //The document properties
$data->properties->title = 'Adventures of Sherlock Holmes';
$data->properties->filename = 'adventures-of-sherlock-holmes.mp4';
$data->properties->mimeType = 'video/mp4';
$data->properties->language = 'en';
$data->properties->createdAt = new \DateTime();
$data->properties->updatedAt = new \DateTime();
$data->properties->size = 204825684; //In bytes
$data->properties->abstract = 'It is a video about the novel about a detective';
$data->properties->thumbnail = 'https://ichef.bbci.co.uk/news/660/cpsprodpb/153B4/production/_89046968_89046967.jpg';
$data->properties->tags = ['tag1', 'tag2']; //Custom tags associated to the data
$data->properties->collections = ['123', '456']; //Search data and browse within the hierarchy

//Start video custom properties
$data->properties->video = new Video();
$data->properties->video->duration = '10 min';

$streaming = new Streaming();
$streaming->type = 'youtube'; //It can be youtube, dash or hls
$streaming->url = 'https://www.youtube.com/watch?v=iEueWyu0TXA';
$data->properties->video->streaming = [$streaming]; //A video can have multiple streaming 

$data->properties->video->source = new Source();
$data->properties->video->source->resolution = '1080';
$data->properties->video->source->format = 'format';
$data->properties->video->source->bitrate = 'bitrate';

$audioEn = new Audio();
$audioEn->language = 'en';
$audioEn->bitrate = '1 Mbps';
$audioEn->formate = 'mp3';

$audioEs = new Audio();
$audioEs->language = 'es';
$audioEs->bitrate = '1 Mbps';
$audioEs->formate = 'mp3';

$data->properties->audio = [
  $audioEn,
  $audioEs,
];

$subtitles = new Subtitles();
$subtitles->language = 'es';
$subtitles->file = 'http://opensubtitles.org/get/iEueWyu0TXA';
$subtitles->format = 'txt';
$data->properties->subtitles = [$subtitles];
//End video custom properties

$data->uploader = new Uploader(); //The originating source where the data has been uploaded or created.
$data->uploader->name = 'Uploader name';

$author = new Author(); //Author
$author->email = 'arthur@conan.doyle';
$author->name = 'Arthur Conan Doyle';
$author->contact = '221B Baker Street';

$data->author = [$author]; //An array with the different document's authors

//We index it
/** @var Data $responseData */
$responseData = $client->add($data);
```

##### Get the document status

The documents indexed by KSearch are searchable by their content and metadata. In order to get the file to be able to process it, KSearch needs to download it and it uses a queue for doing this proccess asynchronously. That's the reason for having a document status.

There are two status:
- `Ok`: Means that the document has been correctly proccessed and indexed by KSearch
- `Queued`: Means that the document stills in the queue for being processed

An example for checking a document status is:

```php
require 'vendor/autoload.php';
use KSearchClient\Client;
use KSearchClient\Http\Authentication;

$client = Client::build($service_url, new Authentication($app_secret, $app_url));

/** @var bool $status */
$status = $client->getStatus('b2c16bd1-6739-4fd9-a1e2-7dde785bed54'); //we have to provide the document UUID
```

##### Remove a document

The following example shows how to delete a document from KSearch with its UUID.

```php
require 'vendor/autoload.php';
use KSearchClient\Client;
use KSearchClient\Http\Authentication;

$client = Client::build($service_url, new Authentication($app_secret, $app_url));

/** @var bool $deleteResult */
$deleteResult = $client->delete('b2c16bd1-6739-4fd9-a1e2-7dde785bed54');
```
##### Get a document

The following example shows how to get a document from KSearch with its UUID.
```php
require 'vendor/autoload.php';

use KSearchClient\Client;
use KSearchClient\Http\Authentication;
use KSearchClient\Model\Data\Data;

$client = Client::build($service_url, new Authentication($app_secret, $app_url));

/** @var Data $data */
$data = $client->get('b2c16bd1-6739-4fd9-a1e2-7dde785bed54');
```

##### Search a document

The KSearch search engine is very flexible. By using filters and agggreagations you can get a lot of info about the documents that are indexed.

In order to perform a search you have to use the `SearchParams` class. There are 3 several properties in a `SearchParams` object:
* `search`: It is a simple string with the content that will be searched in the following the document's content, title and abstract. This property is mandatory.
* `filters`: It is a query in the Lucene query syntax (https://lucene.apache.org/core/2_9_4/queryparsersyntax.html). The fields that can be used for filtering are: `uuid`, `type`, `properties.language`, `properties.created_at`, `properties.updated_at`, `properties.size`, `properties.collections`, `properties.tags`, `properties.mime_type`, `properties.owner.name`, `properties.usage.short`.    
* `aggregations`: It is an array of `Aggregation` objects where the key is the field name (the same fields than in filters are available) where the aggreagation will be applied. In the `Aggregation` object we can configure how many different aggregations we want to return and if the aggregation has to be done before or after the filtering. 

The following example will search the documents that meets those requirements:
* It contains the string "Sherlock" in the title or the content or the abstract.
* Its language is 'es' and its mimetype is 'application/pdf'
 

```php
require 'vendor/autoload.php';

use KSearchClient\Client;
use KSearchClient\Http\Authentication;
use KSearchClient\Model\Data\SearchParams;
use KSearchClient\Model\Data\Aggregation;

$client = Client::build($service_url, new Authentication($app_secret, $app_url));

$searchParams = new SearchParams();
$searchParams->search = 'Sherlock';
$searchParams->filters = 'properties:es AND properties.mime_type:\'application/pdf\'';
$searchParams->aggregations = [];

$languageAggregation = new Aggregation();
$languageAggregation->countsFiltered = false;
$languageAggregation->limit = 15;

$searchParams->aggregations['language'] = $languageAggregation;

$searchParams = new SearchParams();

```

The result will return the found documents and, because we are adding an `Aggregation`, it will also return how many documents exists in each language.

The `$languageAggregation->countsFiltered = false` is important in that case since we are telling KSearch to apply the aggregations before doing the filtering. Otherwise, the aggregation would be applied after the filtering. 


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


## Contributing

Hey, we're accepting Pull Requests, please see our [contribution guide](./CONTRIBUTING.md) for more information.

## License

This project is licensed under the AGPL v3 license, see [LICENSE.txt](./LICENSE.txt).