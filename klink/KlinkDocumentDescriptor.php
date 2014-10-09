<?php


/**
* Define the basic information that describe a document. The official documentation call this a Document Descriptor.
* @package Klink
* @since 0.1.0
*/
final class KlinkDocumentDescriptor
{
	
	public $id;


	public $has;


	public $title;


	public $uri;



	public $abstract;


	public /* KlinkDocumentAuthor[] */ $authors;

	public $type;


	public $institution; /* id or KlinkInstitutionDetails */


	public $thumbnail;


	public $creationDate;


	public $lastModifiedDate;



	function getId();

	function getUrl();

	function getHash();



	function getTitle();

	function getAbstract();

	function getAuthors();

	function getType();


	function getCreationDate();


	function getThumbnail();

	// getLastEditDate

}