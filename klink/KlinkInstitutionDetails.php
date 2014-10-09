<?php 

/**
 * Define the information needed to represent an Institution in Klink. 
 *
 *
 *
 * @package Klink
 * @since 0.1.0
 */

/**
 * Define the information needed to represent an Institution in Klink.
 *
 * This class contains the details of the institution that are known in the Klink, such as the 
 * institution name and contact information.
 * 
 * The institution id must be unique in the Klink Network.
 *
 *
 * @package Klink
 * @since 0.1.0
 */
final class KlinkInstitutionDetails
{
    /**
     * ...
     * @var string
     */	
    public $id;


	/**
     * Full name
     * @var string
     */
    public $name;

    /**
     * ...
     * @var string
     */
    public $mail;

    /**
     * ...
     * @var string
     */
    public $telephone;

    /**
     * ...
     * @var string
     */
    public $type;

    /**
     * The institution address
     * @var Klink_Address
     */
    public $address;

    /**
     * ...
     * @var string
     */
    public $creationDate;

    /**
     * ...
     * @var string
     */
    public $thumbnail;

}

class Klink_Address
{
    public $street;
    public $city;

    public function getGeoCoords()
    {
        //do something with the $street and $city
    }
}