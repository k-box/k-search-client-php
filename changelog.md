# Boilerplate changelog and release info

## Version 3.0.2

If a KlinkFacet is constructed with an empty filter value, the request will be interpreted 
as a facet request and not as a filter. The K-Core will soon throw error if a filter,
with an empty string, is added to the search request.

## Version 3.0.1

Fixed a bug in handling website thumbnail generation

## Version 3.0.0

Version 3.0.0 starts the work on the refactoring of the code. This will end-up in making
the Boilerplate more developer friendly and fully covered by unit tests.

This version enables you to specify the K-Core API version for each configured K-Core. 
In addition adds the option to pass the K-Core API version in some key places, like the 
Facets Builder. 

This is due to the fact that starting from K-Core 2.2.0 the API supports versioning. Versioning 
means that there are differences in the API between old K-Core versions and the new ones, for 
example some new facets are not available in older versions and will raise errors if used.

The Boilerplate do not strip out facets that are not supported on old K-Core versions on purpose. 

**All changes**

- Removed deprecated class `KlinkAuthor`
- Removed deprecated class `KlinkSearchType`
- Removed deprecated class `KlinkHttp`
- Removed deprecated class `KlinkValidators`
- Renamed `phpunit.xml` to `phpunit.xml.dist` and added `phpunit.xml` to git ignore  
- Added `projectIds` field and related methods to the `KlinkDocumentDescriptor`
- `KlinkCoreClient` api changes
 - default value for `$type` in `search()` is now `KlinkVisibilityType::KLINK_PRIVATE`
 - default value for `$visibility` in `facets()` is now `KlinkVisibilityType::KLINK_PRIVATE`
 - added `KlinkCoreClient::DEFAULT_KCORE_API_VERSION` constant for the default api version considered 
   for a K-Core, currently `2.2`
- `KlinkAuthentication` constructor class has a fifth parameter for taking the api version of the K-Core.
  The parameter default value is `KlinkCoreClient::DEFAULT_KCORE_API_VERSION` 
- `KlinkFacetsBuilder` api changes
 - method `all()` now takes an optional string parameter for the K-Core API Version
 - method `allNames()` now takes an optional string parameter for the K-Core API Version
 - added support for `projectId` filtering
 - added support for `documentHash` filtering
- `KlinkFacet` class
 - added support for `projectId` facet with the `PROJECT_ID` constant
 - added `documentHash` filter with the `DOCUMENT_HASH` constant
- `KlinkHelpers` class 
 - Added `is_valid_version_string` to check if a string is a valid version number
 - Added `is_array_of_integers_or_strings` to check if an array contains only integers or strings
 - Added `is_integer_or_string` to check if a mixed variable has a string on an integer value
- `KlinkInstitutionDetails` class
 - method `getAddress()` now returns the full formatted address as a string
 - added `getKlinkAddress()` to obtain the address as `Klink_Address` instance
 - added support for `address` field (see https://git.klink.asia/kcore/kcore/issues/22)
 - method `setAddress()` can accept a string, as full address, or a `Klink_Address` instance
- Now the HTTP stack is entirely based on Guzzle `6.2.x`


## Version 2.3.0

- deprecated class `KlinkValidators`


## Version 2.2.1

**mandatory upgrade**

Fixed a critical bug that affects the network transfer of files between 100KB and 1MB. 
The upgrade to this release is mandatory for users of version 2.2.0. 
Version 2.1 is not affected by this bug. 
After upgrading you must perform a reindex of the files whose size is above or equal to 100KB.

## Version 2.2.0

- added support for identifying .tar, .tar.gz, .gz (extensions and mime types) as archive 
- deprecated class `KlinkDocumentAuthor` and `KlinkSearchType` 
- fixed a warning in `KlinkDocument` when high verbose output is active
- Completed the transition to the [Guzzle](http://docs.guzzlephp.org/en/latest/) library  

## Version 2.1.2

- Fixed a regression in KlinkCoreClient->getInstitutions

## Version 2.1.1

- Added support for using stream to send files.
- Started the transition to the [Guzzle](http://docs.guzzlephp.org/en/latest/) library 

## Version 2.0.0

Major release

### Breaking changes introduced in version 2.0.0

the current `master` is on the 2.x release.

Currently the following breaking changes has been introduced:

- to the `KlinkDocument` class
 - Added the ability to use a resource for the constructor `$data` parameter
 - added `getDocumentStream` 
 - added `getDocumentBase64Stream` 
 - added `isFile` 
- to `KlinkRestClient` and `IKlinkRestClient`
 - `getCollection` parameter order changed to `$url, $expected_return_type, array $params = null`
 - removed `fileSend` empty method
- to `KlinkCoreClient`
 - `generateThumbnailFromContent` can take also a stream for the `$data` parameter
- to `KlinkDocumentUtils`
 - added `getBase64Stream` to get a base64 stream from a string, a file or an existing stream
 - `get_mime`: the method will no longer raise `InvalidArgumentException` if the file do not have an extension, instead will return `application/octet-stream`
 - `getMimeTypeFromExtension`:  the method will no longer raise `InvalidArgumentException` if the extension has no corresponding mime type, instead will return `application/octet-stream`
- to `KlinkCoreClient`
 - `generateThumbnailFromContent` can handle stream for the `$data` parameter

please be aware that `getMimeTypeFromExtension` is used by `get_mime` when the file path specified has the extension. 

### Deprecated

- The class `KlinkHttp` has been deprecated and will be removed in a version 3.0.0.
- The class `KlinkSearchType` and its constants are deprecated. Use the `KlinkVisibilityType` class instead.
- The class `KlinkDocumentAuthor` has been deprecated. No one was using it and the code was a ghost.
