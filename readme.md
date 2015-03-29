# AdapterBoilerplate

> This version will work only on K-Link Cores that supports the API version 2.1

Is the starting point for creating a **K**-Link Adapter. Offers some basic functionality for interacting with the KLink Core API and exposes the main interfaces that represents the data needed for every operation. The use of interfaces has been preferred over classes given the fact that the implementation can be different if an ORM is used or the CMS has some particular requirements.

## Feature offered

the Adapter boilerplate offers a common way to interact with the Klink Core API. The more advanced features will be offered by platform dependant Adapters. You can see the boilerplate as the common part of all adapters.

- Adding and removing documents from the Core
- Updating institutions data
- Full HTTP/Rest stack OOP oriented
- K-Link Core Authentication
- Connectivity test
- Document thumbnail creator service


## Usage

**Composer is used for autoload and bootstrap, so only one file in your project must be included** (please take note that the composer magic works only on php 5.3 or above).


before doing anything please launch

	composer install --prefer-dist

to resolve all the dependencies. The `--prefer-dist` is used to force to resolve the dependencies on the private composer repository and not to donwload from git.

if you don't need to run php unit test with phpunit please use the composer `--no-dev` flag.

Than in your project insert the line

```php
	require_once dirname(__DIR__).'/vendor/autoload.php';
```

### PHP 5.2

If you need to use the Boilerplate on PHP version below the 5.3, at now, you have to perform some basic tasks on an environment with PHP 5.3 and then use the fallback autoloader.

First you have to create a build of the Adapter Boilerplate on a system with PHP >= 5.3 running

	composer install --prefer-dist --no-dev

Now you can use the fallback autoloader that replaces the autoload steps not supported by PHP 5.2 using a class Map approach.

In your project please include the `bootstrap.php` file that you will find in the Adapter Boilerplate directory

```php
	require_once KLINK_BOILERPLATE_FOLDER.'/bootstrap.php';
```

where `KLINK_BOILERPLATE_FOLDER` is the folder that contains the Adapter Boilerplate build.


A loading and connection test for PHP 5.2 is available in the `php52-test.php` file situated in the root directory of the Adapter Boilerplate. To run the test simply use the command line as follows:

	php php52-test.php

after entering the Boilerplate root folder. Make sure that the PHP version invoked is lower than the 5.3 otherwise the composer base autoloading will be executed.

If everything is ok you will see an output like this:

```
-------------------
Testing K-Link Core connection to dev0
   SUCCESS
-------------------
```

Otherwise the detailed error log will be printed.

## Examples

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

- test the http connection to the specified core url.
- test if the given institutionID is a valid identifier on the K-Link Core


```php
$testResult = KlinkCoreClient::test( new KlinkConfiguration( $institutionID, $adapterID, array(
                    new KlinkAuthentication( $core_url, $core_username, $core_password )
                ) ), $error );


if( !$testResult ){

	echo $error->getMessage();

}
```

Here is the list of the error codes reported by the exception in the `$error` out parameter:

| code | description |
|------|-------------|
| 9    | If the basic HTTP connection test fails |
| 10   | If the given intitutionID is not valid |
| 11   | If an exception occurs when testing for the institutionID |


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

In order to generate a thumbnail of the document you can use the static method `KlinkCoreClient::generateThumbnail`. The thumbnail will be in **PNG** format. Consider that only pdf, doc[x], xls[x] and ppt[x] are supported.

This method takes two absolute paths, the first is the path of the document that needs the thumbnail and the second is the path where the generated thumbnail will be saved. The image `$fullImagePath` must contain the file name and the `.png` extension.

```php

KlinkCoreClient::generateThumbnail( 
	$fullFilePath, // The document absolute path
	$fullImagePath // The absolute path of the file where the generated PNG will be saved
	);

```

This method could raise Exception if connection problem to the service occurs.


### Helpers and validators

The KlinkHelpers class if full of useful methods for validation

The KlinkDocumentUtils class contains the hash generation methods


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
- `isMimeTypeSupported( $mime )` test if the specified mime type is supported by the K-Link Core


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

### Document Type values

Here is the list of possible document types:

- `web-page` : A website page or an html document,
- `document` : A generic document (PDF, Word),
- `spreadsheet` : A spreadshett (Excel),
- `presentation` : A presentation (PowerPoint),
- `image` : An image (jpg, gif or png).


---------------------------

## Unit Test

**Unit tests can only be executed on PHP 5.3 or above**

to run Unit Tests you must have phpunit version 4.3 or above and the php configuration must have the following extension enabled:

- `php_gd2` for imaging functions with full png support (if you are on Mac OS Yosemite you might have GD bundled, but with no png support)

Some tests maybe skipped depending on your environment configuration (for example specific tests for linux environment will be skipped when executed on Windows).

All tests have been executed on the following versions of PHP

- 5.3.14
- 5.4.4
- 5.5
- 5.6 with curl enabled


Unit tests are peformed on repository push and build with the following configuration:

K-Link Core used: klink.dev0.cloudapp.net

to run the unit tests by yourself you have to perform

	composer install --prefer-dist

and let composer download also the required-dev dependencies. After that you can invoke

	php vendor/bin/phpunit

to execute the tests with yout version of php (of any version of PHP)





