# Boilerplate changelog and release info

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
