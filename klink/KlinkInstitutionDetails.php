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
     * @var string
     */
    public $addressStreet;

    public $addressCountry;

    public $addressLocality;

    public $addressPostalCode;

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


    function __construct(){

    }

}

class Klink_Address
{
    public $street;
    public $country;
    public $locality;
    public $postalCode;

    // addressCountry  Country     The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
    // addressLocality Text    The locality. For example, Mountain View.
    // addressRegion   Text    The region. For example, CA.
    // postOfficeBoxNumber Text    The post office box number for PO box addresses.
    // postalCode  Text    The postal code. For example, 94043.
    // streetAddress   Text    The street address. For example, 1600 Amphitheatre Pkwy.

    public function getGeoCoords()
    {
        //do something with the $street and $city
    }
}