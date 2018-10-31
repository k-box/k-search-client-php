# K-Search client changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/).

This project **_do not adhere_ to Semantic Versioning**. The `Major` version number 
indicates the supported K-Search API version, e.g. a version number `3` means that
the K-Search client supports K-Search api version `>=3.0`, but less than `4.0`.

## Unreleased

## [3.3.0] - 2018-10-31

### Changed

- The client now send requests with API version 3.6.

## [3.2.0] - 2018-09-26

### Added

- Support for K-Search API 3.5
 - Support for `geo_location_filter` inside the Search request
 - Support for the new `geo_location` field in the Data model

### Changed

- K-Search client now asks for API version 3.5 (requires K-Search version 3.5.0) (**breaking change**)

## [3.1.0] - 2018-07-20

### Added

- Support for K-Search API 3.4
- Sorting parameter to search request
- Filters and Aggregations enumerations with usable filters and aggregations

### Changed

- K-Search client now asks for API version 3.4 (requires K-Search version 3.3.0) (**breaking change**)
- Move `KSearchClient\Model\Data\Search` to `KSearchClient\Model\Search` (**breaking change**)

### Removed

- Class `Facet`, replaced by `Model\Search\Aggregation` (**breaking change**)
- Class `FacetItem`, replaced by `Model\Search\AggregationResult` (**breaking change**)
- Class `ResultItem`, replaced by `Model\Data\Data` (**breaking change**)
- Class `ResultSet`, replaced by `Model\Search\SearchResults` (**breaking change**)
- Class `SearchItem` (**breaking change**)

## [3.0.2] - 2018-07-13

### Added

- `DeserializationException` to better expose the JSON deserialization failure.
  The message also contains the original received JSON.

### Changed

- Improved error handling in case an HTML response is returned by the server

### Fixes

- Add request id generation for all requests ([#2](https://github.com/k-box/k-search-client-php/pull/2))

## [3.0.1] - 2018-01-19

### Added

- `website` field to the `CopyrightOwner` object.

### Changed

- Renamed `CopyrightOwner.contact` to  `CopyrightOwner.address`. **breaking change**

## [3.0.0] - 2017-11-14

### Added

- New Client implementation

### Changed

- Composer package name from `klink/adapterboilerplate` to `k-box/k-search-client-php`

### Removed

- Support for K-Search API version `2`
