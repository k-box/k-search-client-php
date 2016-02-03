[![build status](https://git.klink.asia/ci/projects/7/status.png?ref=master)](https://git.klink.asia/ci/projects/7?ref=master)

# Adapter Boilerplate

Is the starting point for creating a K-Link Adapter and/or using the K-Link Core services, in general. 
It offers some basic functionality for interacting with the K-Link Core and exposes the main interfaces that represents the data needed for every operation. The use of interfaces has been preferred over classes given the fact that the implementation can be different if an ORM is used or the CMS has some particular requirements.

**requires the latest K-Link Core version**

**This readme refers to the version 1.0 of the Adapter Boilerplate, the old version (0.3.x) is in the `legacy` branch only for bugfixes and compatibility reasons.**

## Feature offered

The Adapter Boilerplate is a library that enable developers to interact with the K-Link Core.

- Adding and removing documents from the Core
- Adding/Updatting/Removing institutions data
- Search with facets and filters
- Full HTTP/Rest stack OOP oriented
- K-Link Core Authentication
- Connectivity test
- Document thumbnail creator service


## Requirements

- PHP 5.5.9 or above
- PHP GD library for image support (some PHP installation might not have the library bundled)
- Network connectivity

Tested on PHP 5.5, 5.6.5 on Windows, Mac OS (Yosemite) and Ubuntu 14.04.


## Usage

The K-Link Adapter Boilerplate utilizes [Composer](http://getcomposer.org/) to manage its dependencies. So, before using the Boilerplate, make sure you have Composer installed on your machine.

The K-Link Adapter Boilerplate is available in the K-Link composer private repository. In order to require it in your project add the following repository configuration to your `composer.json` file.

```json
"repositories": [
    {
        "type": "composer",
        "url": "http://repo.klink.dyndns.ws/"
    }
]
```

Now you could require the Adapter Boilerplate 

    composer require --prefer-dist klink/adapterboilerplate



## Usage Examples of the Boilerplate features

To use the K-Link Core services all you need is to interact with the `KlinkCoreClient` class.

## basic initialization

```php
$config = new KlinkConfiguration( 
	'InstitutionID', // The institution identifier
	'InstitutionAdapterID', // The adapter identifier
	array( // The array of K-Link Cores to be used
		new KlinkAuthentication(
			'http://klink-core0.cloudapp.net/kcore/', // The url of the core
			'username', // Username of the core
			'password'  // The password used for authentication
		)
	) );

$klinkCore = new KlinkCoreClient( $config );
```

Every constructor can raise an `InvalidArgumentException` if the passed argument is not valid. In particular the `InstitutionID` and the `InstitutionAdapterID` must be a valid identifier, i.e. a non empty alphanumeric string with dashes.

The K-Link Core url inserted in the code block is for example purposes only.


### test a configuration

The KlinkCoreClient class exposes a static method for performing a basic service test on the configuration. This test must be done prior to save the configuration.

Actually the test method supports only the usage of one core in the cores array of the `KlinkConfiguration` constructor.

The test method returns `true` if all the test passed and `false` otherwise. In the `$error` parameter the full detailed exception is stored.

The test method will perform the following steps:

- test the http connection to the specified core url (by retrieving the institutions list).
- test if the given institutionID is a valid identifier on the K-Link Core.


```php
$testResult = KlinkCoreClient::test( new KlinkConfiguration( $institutionID, $adapterID, array(
                    new KlinkAuthentication( $core_url, $core_username, $core_password )
                ) ), $error );


if( !$testResult ){

	echo $error->getMessage();

}
```

If an error occur during the test, the `$error` (out) parameter will contain an exception whose code is the HTTP Status code of the response.

In some cases the Exception message will report a specific error:

| code | message  | description |
|------|----------|-------------|
| 401  | Wrong username or password.   | If the specified credentials are not valid |
| 403  | Unauthorized to read the Institution details.   | If the user cannot access institution details |
| 404  | Wrong Institution Identifier or Institution Details not available on the selected K-Link Core   | If the given intitutionID is not valid |

In all the other case a general error message, "Server not found or network problem.", is reported along with the HTTP Error code that was the cause of the test failure.

The reported exception will have information about the [previous exception](http://php.net/manual/en/exception.getprevious.php) that has caused the failure and the code of the exception will always be the HTTP error code.

### perform a search

Assuming that the `$klinkCore` variable is a valid instance of `KlinkCoreClient`, the search is performed using the `search` method.

```php
$searchResult = $klinkCore->search( $searched_term );
```

The `$searchResult` will contain an instance of `KlinkSearchResult` that contains the `KlinkDocumentDescriptors` found and the pagination details.

To get the result item of a search you need to call

```php
/* KlinkDocumentDescriptors[] */ $results = $searchResult->getResults();
```

To get the total numer of results and the pagination information you need to invoke these methods:

```php
/* 
	The grand total of results matched by the query 
*/
$totalFound = $searchResult->getTotalResults();

/*
	the number of results to retrieve, if no value is given the default value of 10 is used
 */
$resultsPerPage = $searchResult->getResultsPerPage() 

/*
     specify the index of the first result of the total result set for the search. This value is used for retrieve the other pages. The value is 0-based; the default value is 0.
 */
$start = $searchResult->getOffset();

```

Considering that the results could be more than what fits in a result page, you have the ability to select which page of the result set need to be displayed:

```php
$searchResultSecondPage = $klinkCore->search($searched_term, KlinkSearchType::KLINK_PUBLIC, $resultsPerPage, $start + $resultsPerPage );
```

The first `$resultsPerPage` is the number of results per page and the `$start` is the offset of the first new result.

It highly suggested to use the results per page and offset value of the current `KlinkSearchResult` instance because the number of results per page and the offset could vary between different searches.


### getting institution details

All you need to get the details of an institution is the institution identifier (`$id` in the code block).

```php
$details = $klinkCore->getInstitution( $id );
```

In `$details` you will have an instance of `KlinkInstitutionDetails`.


### add a document

To perform the add of a document you need to 
- create an instance of `KlinkDocumentDescriptor` (using `KlinkDocumentDescriptor::create()`) with the required details;
- encapsulate the constructed descriptor in a `KlinkDocument` instance feeded with path of the document on the file system;
- use the `addDocument` method to send the file for indexing.

The addDocument will return an updated KlinkDocumentDescriptor with values in some optional fields (e.g. language)

```php

$filePath = "/uploads/document.pdf"; // the file path

// ... only a few of the used variables will be showed here because there are helpers function you can use

$hash = KlinkDocumentUtils::generateDocumentHash( $filePath );

$mime_type = KlinkDocumentUtils::get_mime( $filePath );

// first create a document descriptor

$documentDescriptor = KlinkDocumentDescriptor::create(
    		$institution_id, //the institution identifier, must be the same used for the KlinkCoreClient configuration
    		$local_document_id,  // must be alphanumeric
    		$hash, // The SHA-2 hash of the file. Please use KlinkDocumentUtils::generateDocumentHash( $filePath )
    		$title, //the document title
    		$mime_type, 
    		$document_uri, //the public URI of the document
    		$thumbnail_uri, //the public URI of the thumbnail
    		$user_uploader, //the user that has uploaded the file in the format: "user <mail@mail.com>"
    		$user_owner,  //the user that can be contacted for info about the document in the format: "user <mail@mail.com>"
    		KlinkVisibilityType::KLINK_PUBLIC, // the visibility of the document
    		KlinkHelpers::format_date( $creation_date ) //the creation date of the file
    	);

// To correctly format the date please use the KlinkHelpers::format_date(...) method

// create a document instance that encapsulate the document descriptor and the real file

$document = new KlinkDocument($documentDescriptor, $filePath);

// add the document to the core

$newDocumentDescriptor = $klinkCore->addDocument( $document );
```

### Get Institution statistics

The KlinkCoreClient class can also give some basic aggregated statistics for an institution given it's id

#### Get the number of public documents

```php
$count = $klinkCore->getPublicDocumentsCount( $id );
$count = $klinkCore->getPublicDocumentsCount(); // the currently configured institution identifier is assumed
```

#### Get the number of private documents

```php
$count = $klinkCore->getPrivateDocumentsCount( $id );
$count = $klinkCore->getPrivateDocumentsCount(); // the currently configured institution identifier is assumed
```

### generate a thumbnail

In order to generate a **PNG** thumbnail of the document you can use the following methods:

- `generateThumbnail`: generate a thumbnail given the source file and the output file;
- `generateThumbnailFromContent`: generate a thumbnail of the given file content;
- `generateThumbnailFromDocument`: generate a thumbnail of a KlinkDocument;
- `generateThumbnailOfWebSite`: generate a thumbnail that is the screenshot of the specified url/web page (must be an html page).

Supported file formats:

`generateThumbnail`, `generateThumbnailFromContent` and `generateThumbnailFromDocument` supports pdf, docx, xlsx, pptx and the following image formats: gif, png and jpg. Please note that the image formats are only supported on PHP 5.3 or above with the GD extension enabled (the GD library must be compiled for supporting gif, jpg and png formats and in particular **the PNG support is mandatory** given that the thumbnail output is in PNG format).

`generateThumbnailOfWebSite` only supports URL that have the output in HTML format (Don't feed with url that output PDFs or something else).


#### `generateThumbnail`

This method takes two absolute paths, the first is the path of the document that needs the thumbnail and the second is the path where the generated thumbnail will be saved. The image `$fullImagePath` must contain the file name and the `.png` extension.

```php
$client = new KlinkCoreClient( /* ... */);

$image_file_path = $client->generateThumbnail( 
	$fullFilePath, // The document absolute path
	$fullImagePath // The absolute path of the file where the generated PNG will be saved
	);
```

This method could raise:
- `InvalidArgumentException` if at least one of the required parameter have a wrong value
- `KlinkException` if connection problem to the service occurs.

#### `generateThumbnailFromContent`

This method requires two parameters: the first is the mime type of the content and the second is the data for which you would have the thumbnail.

The method returns the generated image content.

```php
$client = new KlinkCoreClient( /* ... */);

$file_path = '/a/file.pdf';

$data = file_get_contents($file_path);

$mime_type = KlinkDocumentUtils::get_mime($file_path);

$image_content = $client->generateThumbnailFromContent( 
	$mime_type, // The mime type of the given data
	$data // the data
	);
```

This method could raise:
- `InvalidArgumentException` if at least one of the required parameter have a wrong value
- `KlinkException` if connection problem to the service occurs.



#### `generateThumbnailFromDocument`

Generates the thumbnail of the given `KlinkDocument`. The method returns the generated image content.

```php
$client = new KlinkCoreClient( /* ... */);

$descriptor = KlinkDocumentDescriptor::create( /* ... */ );

$document = new KlinkDocument($descriptor, 'Content OR file path');

$image_content = $client->generateThumbnailFromDocument( 
	$document // The document
	);
```

**Attenction** `generateThumbnailFromDocument` attemp to get the documentData from the `KlinkDocument`, if a valid file path was specified as the document data it will try to load the file content using php [file_get_contents](http://php.net/manual/en/function.file-get-contents.php).

This method could raise:
- `InvalidArgumentException` if at least one of the required parameter have a wrong value
- `KlinkException` if connection problem to the service occurs.


#### `generateThumbnailOfWebSite`

This method has 1 required paramter which is the `$url` of the page. The second parameter, `$image_file`, is optional and if specified must be a valid path where the thumbnail will be saved.

This method returns the image content in PNG format if `$image_file` parameter is null or the [file_put_contents](http://php.net/manual/en/function.file-put-contents.php) return value if a file path is specified in the `$image_file` parameter.

```php
$client = new KlinkCoreClient( /* ... */);

$url = 'http://www.google.com';

$image_content = $client->generateThumbnailOfWebSite( 
	$url // The web page URL
	);
```

This method could raise:
- `InvalidArgumentException` if at least one of the required parameter have a wrong value
- `KlinkException` if connection problem to the service occurs.




### Helpers and validators

The `KlinkHelpers` class if full of useful methods for validation

The `KlinkDocumentUtils` class contains the **hash generation methods**


#### KlinkHelpers

The KlinkHelpers class has the following utility methods that you need to be aware of:

- `sanitize_string( $string )` Perform the sanitation of a string given by user input
- `absint( $string )` convert a string to a positive integer
- `now()` return the current date and time formatted as RFC3339
- `format_date( $date_string )` return the given date formatted as RFC3339
- `string_ends_with( $haystack, $needle )` test if a string ends has a particular suffix




#### KlinkDocumentUtils

The KlinkDocumentUtils class has the following utility methods that you need to be aware of:

- `generateDocumentHash( $filePath )` generates the hash of the content of a document
- `generateHash( $text )` generates the hash of the given text
- `get_mime( $filePath )` return the mime type of a file
- `isMimeTypeSupported( $mime )` **test if the specified mime type is known and can be handled. This not guarantee that the the K-Link Core can index the file without prior elaboration**
- `isMimeTypeIndexable( $mime )` **test if the specified mime type is supported by the K-Link Core and requires no elaboration before can be sent to the K-Link Core**

**Important notice**

In the course of the following month we will add the ability to recognize more document types than what the search engine can handle directly. So we decided to separate the mime type support check in two different functions.
The `isMimeTypeSupported` function will tell if we can recognize that file from the mime type, but will not guarantee that you can send the file directly to the K-Link Core. 
To check if you need to extract the content of the file before sending the document to the K-Link Core you have to use the `isMimeTypeIndexable` function, if this function return false you have to extract the textual content of the file by yourselve before sending a document add request to the K-Link Core. 


## Facets

Facets are specified as instances of the class KlinkFacet. To create an instance of the KlinkFacet class use the create method

```php
	/**
	 * Create a new facet instance.
	 * 
	 * For the facet name plase refer to @see KlinkFacet class constants
	 * 
	 * @param string $name   the name of the facet, see the constants defined in this class
	 * @param int $min Specify the minimun frequency for the facet-term to be returned, default 2
	 * @param string $prefix retrieve the facet items that have such prefix in the text 
	 * @param int $count  configure the number of terms to return for the given facet
	 * @param string $filter specify the filtering value to applied to the search for the given facet
	 * 
	 * @throws InvalidArgumentException If $name if not a valid facet name @see KlinkFacet
	 */
	public static function create($name, $min = 2, $prefix = null, $count = 10, $filter = null)

```

To specify the facet name please make use of the constants defined in the class `KlinkFacet` (some are highlighted in the code block below) or use the `KlinkFacetsBuilder`

```php

// create a default instance for the document type field

$facet_one = KlinkFacet::create(KlinkFacet::DOCUMENT_TYPE);

// create a custom instance for the document type field

$facet_two = KlinkFacet::create(KlinkFacet::DOCUMENT_TYPE, 10, 'prefix', 12, 'filter');

```




```php

	/**
	 * Define the facet name for the @see KlinkDocumentDescriptor::$documentType field
	 */
	const DOCUMENT_TYPE = 'documentType';

	/**
	 * Define the facet name for the @see KlinkDocumentDescriptor::$language field
	 */
	const LANGUAGE = 'language';

	/**
	 * Define the facet name for the @see KlinkDocumentDescriptor::$institutionId field
	 */
	const INSTITUTION_ID = 'institutionId';

```

### Klink Facets builder

The facets builder enable the fluent creation of the array of KlinkFacet needed for the search or for the direct facets extraction (`facets()`).

The facets builder also check for basic validation of the parameters, as an example the documentType facet must receive a valid document type for filtering and the institutionId facet requires a valid istitution identifier.

As you might have seen in the previous section, creating an array of facets could be tedious and time consuming. The aim of the facets builder is to enable a faster approach to facets parameter creation.

#### Get all the supported facets

```php

	$facets_array = KlinkFacetsBuilder::all();

```


#### Supported facets and parameters

The builder have a method for each supported facet:

- `documentType()` for the documentType field in KlinkDocumentDescriptor;
- `language()` for the language field in KlinkDocumentDescriptor;
- `institution()` for the institutionId field in KlinkDocumentDescriptor;

Each method is called a *facet buidling method* and return the instance of the KlinkFacetsBuilder to enable method chaining.

To get the builded array of KlinkFacets call the `build()` method on an instance of KlinkFacetsBuilder.

```php

	$array_of_facets = KlinkFacetsBuilder::create()->documentType()->build();

	$array_of_facets = KlinkFacetsBuilder::create()->documentType()->language('en')->build();

	$array_of_facets = KlinkFacetsBuilder::create()->institution('KLINK')->documentType('presentation')->build();

```


Each facet building method could receive a variable number of parameters. Here is the general rule:

- no parameters -> default behaviour with mincount = 2 and count = 10, no filter
- 1 parameter of type string -> assumed as filter, other values are at it's defaults
- 1 parameter of type int -> assumed as the mincount (the minimun frequency for the facet-term to be returned)
- 2 parameters of type int -> first the count and second the mincount
- 3 parameters, the first of type string and the others of type int -> 1: filter, 2: count, 3: mincount


**please note that in version 0.3.9 and below the single integer parameter case behaviour was to set the `count` parameter of the facet**.


### Filters

For commodity there are also some filters (that cannot be invoked as facets) that are supported:

- localDocumentId (`KlinkFacet::LOCAL_DOCUMENT_ID`): enable to select a specific set of documents given the local document identifier (`KlinkDocumentDescriptor::getLocalDocumentID()`) before executing a search; The search will be executed over the specified collection.
- documentId (`KlinkFacet::DOCUMENT_ID`): enable to select a specific set of documents identified by the K-Link Document Identifer ((`KlinkDocumentDescriptor::getKlinkId()`) before applying search parameters; The search will be executed over the specified collection.

On filters you can only specify the filtering parameter and not the mincount and count parameter as for normal facets.

The `KlinkFacetBuilder` class supports also adding filters. Filters can be mixed with facets. The filter parameter can be a single string value or an array of strings.


```php

	// filter for the local document identifier 10
	$array_of_facets = KlinkFacetsBuilder::create()->localDocumentId('10')->build();

	// filter for the local document identifiers 10 and 12
	$array_of_facets = KlinkFacetsBuilder::create()->localDocumentId(['10', '12'])->build();

	// filter for the K-Link Document identifier
	$array_of_facets = KlinkFacetsBuilder::create()->documentId('KLINK-10')->build();
	// remember that the K-Link document identifer is composed by institutionID followed by a dash and the local document identifier
	$array_of_facets = KlinkFacetsBuilder::create()->documentId('CA-10')->build();


```


### Examples

```php

	// enabling the “language” facet
	KlinkFacetsBuilder::create()->language()->build();

	// enabling the “language” facet and retrieve only the 3 most frequent facets of such field using the default mincount attribute
	KlinkFacetsBuilder::create()->language(3, KlinkFacetsBuilder::DEFAULT_MINCOUNT)->build();	
	//or using a custom mincount. In this case the minimum frequency for considering the facet is 1, so if there is at least one document the facet will be returned
	KlinkFacetsBuilder::create()->language(3, 1)->build();	

	// filtering the documents by documentType “presentation”
	KlinkFacetsBuilder::create()->documentType('presentation');

	// filtering the documents by documentType “presentation” or document”
	KlinkFacetsBuilder::create()->documentType('presentation,document');

```

Please consider that you will receive a `BadMethodCallException` if you call more than once a facet building method.


#### know potential breaking situations

if you will have an error like this (with error reporting all active)

	Non-static method KlinkFacetsBuilder::documentType() should not be called statically, assuming $this from incompatible context

is probably caused by the fact that in PHP 5.6 (at least to the authors knowledge) methods with or without the static modifier are the same

For example 1 and 2 are the same method and 2 gives a syntax error because is considered the same as 1.

```php

	public function documentType() { } // 1

	public static function documentType() { } // 2

```

At the time of writing seems that older versions of PHP calls the __callStatic magic method (feel free to report if not true) to get the correct 
instance of the builder before invoking the method.

To solve this problem you can do

```php

	// 1 create an instance by yourself
	$builder = (new KlinkFacetsBuilder)->documentType();

	// 2 use the instance method 
	$builder = KlinkFacetsBuilder::create()->documentType();

	// 3 use the shortcut instance method, called i 
	$builder = KlinkFacetsBuilder::i()->documentType();	

```

---------------------------

## Constants and values

### Public and Private visibility types

The Boilerplate makes available the following constants for specifying the visibility of the search and the document.

- `KlinkVisibilityType::KLINK_PRIVATE`: represents the document (and search) private visibility
- `KlinkVisibilityType::KLINK_PUBLIC`: represents the document (and search) public visibility, which means that the requests will be made to the K-Link Public service


### Document Type values

Here is the list of possible document types:

- `web-page` : A website page or an html document,
- `document` : A generic document (PDF, Word),
- `text-document` : A generic textual document (TXT files, RTF files, Markdown files),
- `spreadsheet` : A spreadshett (Excel),
- `presentation` : A presentation (PowerPoint),
- `image` : An image (jpg, gif or png).
- `geodata` : Google Earth Files (KMZ, KML).

### Deprecated

- The class `KlinkSearchType` and its constants are deprecated. Use the `KlinkVisibilityType` class instead. 


---------------------------

## Unit Test

The Adapter Boilerplate has unit test that covers the main

to run Unit Tests you must have phpunit version 4.8 or above and the php configuration must have the following extension enabled:

- `php_curl`
- `php_gd2` for imaging functions with full png support (if you are on Mac OS Yosemite you might have GD bundled, but with no png support)

Some tests maybe skipped depending on your environment configuration (for example specific tests for linux environment will be skipped when executed on Windows).

The unit test collection is categorized in the following groups:

- default: normal unit tests that can be executed without network or backend availability
- deserialization: unit tests that performs JSON deserialization check
- http: unit tests that performs HTTP calls to http://httpbin.org/ (and therefore requires network connectivity)
- integration: test that requires an active K-Link Core instance (this tests are not executed by the default configuration)


All tests have been executed on the following versions of PHP

- 5.5
- 5.6.5 with curl enabled


Unit tests are peformed on repository push and build with the following configuration (see `phpunit.xml` file):

INSTITUION_ID: BOIL

to run the unit tests by yourself you have to perform

	composer install --prefer-dist

and let composer download also the required-dev dependencies. After that you can invoke

	vendor/bin/phpunit

to execute the tests with your version of PHP.

#### Integration tests

**Integration tests with a real K-Link Core instance are not automatically executed**. In order to be able to execute the integration tests the following environment variables must be setup:

- `CORE_URL`: the address of the K-Link private Core
- `PUBLIC_CORE_URL`: the address of the K-Link Public 
- `CORE_USER`: the username to be used for authentication 
- `CORE_PASS`: the password used for authentication

to execute the integration tests

	vendor/bin/phpunit --group integration
