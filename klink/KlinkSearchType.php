<?php

/**
 * Define the available types of search.
 *
 * @package Klink
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

}