# AdapterBoilerplate

Is the starting point for creating a **K**Link Adapter. Offers some basic functionality for interacting with the KLink Core API and exposes the main interfaces that represents the data needed for every operation. The use of interfaces has been preferred over classes given the fact that the implementation can be different if an ORM is used or the CMS has some particular requirements.

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

	composer update --prefer-dist

to resolve all the dependencies. The `--prefer-dist` is used to force to resolve the dependencies on the private composer repository and not to donwload from git.

Than in your project insert the line

	require_once dirname(__DIR__).'/vendor/autoload.php';


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


Considering the results pagination to get the second page is requested as follows:

```php
$searchResultSecondPage = $klinkCore->search($searched_term, KlinkSearchType::KLINK_PUBLIC, 10, 10 );
```

The first `10` is the number of results per page and the second is the offset of the first new result.


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

// first create a document descriptor

$documentDescriptor = KlinkDocumentDescriptor::create(
    		$institution_id, //the institution identifier, must be the same used for the KlinkCoreClient configuration
    		$local_document_id,  // must be alphanumeric
    		$hash, // The SHA-2 hash of the file. Please use KlinkDocumentUtils::generateDocumentHash( $filePath )
    		$title, //the document title
    		$this->mime_type, 
    		$this->document_uri, //the public URI of the document
    		$this->thumbnail_uri, //the public URI of the thumbnail
    		$this->user_uploader, //the user that has uploaded the file in the format: "user <mail@mail.com>"
    		$this->user_owner,  //the user that can be contacted for info about the document in the format: "user <mail@mail.com>"
    		KlinkVisibilityType::KLINK_PUBLIC, // the visibility of the document
    		KlinkHelpers::format_date( $creation_date ) //the creation date of the file
    	);

// To correctly format the date please use the KlinkHelpers::format_date(...) method

// create a document instance that encapsulate the document descriptor and the real file

$document = new KlinkDocument($documentDescriptor, $filePath);

// add the document to the core

$newDocumentDescriptor = $klinkCore->addDocument( $document );
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




