<?php

namespace KSearchClient\Model\Search;

use KSearchClient\HasEnums;


/**
 * The filter names that can be used during a search
 */
class Filters
{
	use HasEnums;

    const MIME_TYPE = 'properties.mime_type';

	const LANGUAGE = 'properties.language';

	const UPLOADER_NAME = 'uploader.name';
	
	const UPLOADER_ORGANIZATION = 'uploader.organization';

	const COLLECTIONS = 'properties.collections';
	
	const TAGS = 'properties.tags';

	const CREATED_AT = 'properties.created_at';

	const UPDATED_AT = 'properties.updated_at';

	const SIZE = 'properties.size';

	const COPYRIGHT_OWNER_NAME = 'copyright.owner.name';
	
    const COPYRIGHT_USAGE_SHORT = 'copyright.usage.short';
    
	const UUID = 'uuid';
	
	const TYPE = 'type';
	
}