<?php


/**
* Define the basic information that describe a document. The official documentation call this a Document Descriptor.
* @package Klink
* @since 0.1.0
*/
final class KlinkDocumentDescriptor
{

//

	/**
	 * 
	 * 
	 * */


	/**
	 * id
	 * @var string
	 */

	private $id;

	/**
	 * getId
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * hash
	 * @var string
	 */

	private $hash;

	/**
	 * getHash
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * title
	 * @var string
	 */

	private $title;

	/**
	 * setTitle
	 * @param $value
	 * @return void
	 */
	public function setTitle($value) {
		$this->title = $value;
	}
	/**
	 * getTitle
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * uri
	 * @var string
	 */

	private $uri;

	/**
	 * getUri
	 * @return string
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 * abstract
	 * @var string
	 */

	private $abstract;

	/**
	 * setAbstract
	 * @param $value
	 * @return void
	 */
	public function setAbstract($value) {
		$this->abstract = $value;
	}
	/**
	 * getAbstract
	 * @return string
	 */
	public function getAbstract() {
		return $this->abstract;
	}

	/**
	 * Reference person.
	 * 
	 * The person to contact for getting details
	 * @var KlinkDocumentAuthor
	 */

	private $referencePerson;

	/**
	 * getReferencePerson
	 * @return KlinkDocumentAuthor
	 */
	public function getReferencePerson() {
		return $this->referencePerson;
	}

	/**
	 * authors
	 * @var KlinkDocumentAuthor[]
	 */

	private $authors;

	/**
	 * setAuthors
	 * @param KlinkDocumentAuthor[] $value
	 * @return void
	 */
	public function setAuthors(array $value) {
		$this->authors = $value;
	}
	/**
	 * getAuthors
	 * @return KlinkDocumentAuthor[]
	 */
	public function getAuthors() {
		return $this->authors;
	}

	/**
	 * type
	 * @var string
	 */

	private $type;

	/**
	 * setType
	 * @param $value
	 * @return void
	 */
	public function setType($value) {
		$this->type = $value;
	}
	/**
	 * getType
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * institution
	 * @var KlinkInstitutionDetails
	 */

	private $institution;

	/**
	 * Owner institution
	 * @return KlinkInstitutionDetails
	 */
	public function getInstitution() {
		return $this->institution;
	}

	/**
	 * creationDate
	 * @var Date
	 */

	private $creationDate;

	/**
	 * setCreationDate
	 * @param Date $value
	 * @return void
	 */
	public function setCreationDate(Date $value) {
		$this->creationDate = $value;
	}
	/**
	 * getCreationDate
	 * @return Date
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * lastModified
	 * @var Date
	 */

	private $lastModified;

	/**
	 * setLastModified
	 * @param Date $value
	 * @return void
	 */
	public function setLastModified(Date $value) {
		$this->lastModified = $value;
	}
	/**
	 * getLastModified
	 * @return Date
	 */
	public function getLastModified() {
		return $this->lastModified;
	}

	



}