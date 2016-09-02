<?php

/**
 * Define the available types of search.
 *
 * @package Klink
 * @deprecated 2.2.0 No longer used by internal code and not recommended, will be removed from the next major release. Use KlinkVisibilityType instead
 * @see KlinkVisibilityType for a replacement  
 */
final class KlinkSearchType
{

	/**
	 * Local search type. 
	 * 
	 * Only local institution documents are searched
	 */	
	const KLINK_PRIVATE = 'private';

	/**
	 * Global (All) search type.
	 * 
	 * The search is performed on publica documents from all the institutions in KLink
	 */
	const KLINK_PUBLIC = 'public';



	// TODO: method for check visibility value
}