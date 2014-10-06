<?php namespace Klink;


/**
* Define the basic information that describe a document. The official documentation call this a Document Descriptor.
* @package Klink
* @since 0.1.0
*/
interface IDocument 
{
	
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