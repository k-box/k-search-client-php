<?php namespace Klink;

/**
 * Define the available types of search.
 *
 * @package Klink
 */
abstract class SearchType
{

	/**
	 * Local search type. 
	 * 
	 * Only local institution documents are searched
	 */	
	const LOCAL = 'local';

	/**
	 * Global (All) search type.
	 * 
	 * The search is performed on publica documents from all the institutions in KLink
	 */
	const ALL = 'global';

}