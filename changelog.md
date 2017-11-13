# Changelog and release info

Release versioning schema has been changed. Now the major version number refers to the K-Core/K-Search api specification.

## Version 2.30.0

- If a KlinkFacet is constructed with an empty filter value, the request will be interpreted 
as a facet request and not as a filter. The K-Core will soon throw error if a filter,
with an empty string, is added to the search request.
- Fixed a bug in handling website thumbnail generation
- Enabled to specify the K-Core API version for each configured K-Core.
- Removed strict validation check on document types passed to the `documentType` filter when 
  using the `KlinkFacetsBuilder`

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

