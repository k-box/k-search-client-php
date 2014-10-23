<?php

/**
 * Define the available types of search.
 *
 * @package Klink
 */
final class KlinkVisibilityType
{

	/**
	 * Private document
	 * 
	 * The document will be available only to the institution that uploaded it
	 */	
	const KLINK_PRIVATE = 'private';

	/**
	 * Public document.
	 * 
	 * The document will be visibile from all the institution in KLink
	 */
	const KLINK_PUBLIC = 'public';

}