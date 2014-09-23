<?php


/**
* Define the basic information that describe a document. The official documentation call this a Document Descriptor.
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