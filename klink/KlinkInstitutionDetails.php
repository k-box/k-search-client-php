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
    public $phone;

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
    public $thumbnailURI;


    function __construct(){

    }


    /**
     * Returns the address of the institution.
     * 
     * @return Klink_Address
     */
    public function getAddress(){

        return new Klink_Address(
            $this->addressStreet, 
            $this->addressCountry, 
            $this->addressLocality, 
            $this->addressPostalCode);

    }

    public function setAddress(Klink_Address $address){
        $this->addressStreet = $address->street;
        $this->addressCountry = $address->country;
        $this->addressLocality = $address->locality;
        $this->addressPostalCode = $address->postalCode;

        return $this;
    }

    /**
     * The type of the institution according to schema.org
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function setType( $type )
    {
        $this->type = $type;
        return $this;
    }

    /**
     * The name of the institution
     * @return type
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * The institution telephone number
     * @return type
     */
    public function getPhoneNumber()
    {
        return $this->phone;
    }

    public function setPhoneNumber($number)
    {
        KlinkHelpers::is_valid_phonenumber( $number, 'phone number' );

        $this->phone = $number;
    }

    /**
     * The institution e-mail address for contact purposes
     * @return type
     */
    public function getMail()
    {
        return $this->mail;
    }

    public function setMail( $mail )
    {
        KlinkHelpers::is_valid_mail( $number, 'phone number' );

        $this->mail = $mail;

        return $this;
    }

    /**
     * The institution ID
     * @return type
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * The image that represents the institution
     * @return string|null the url of the public institution image if configured, null otherwise
     */
    public function getThumbnail()
    {
        return $this->thumbnailURI;
    }

    public function setThumbnail($thumbnailURI)
    {
        $this->thumbnailURI = $thumbnailURI;

        return $this;
    }

    /**
     * The date on which the institution has joined the KLink
     * @return DateTime
     */
    public function getJoinedDate()
    {
        return date_create( $this->creationDate );
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        KlinkHelpers::is_valid_url( $url, 'url' );

        $this->url = $url;
    }




    public function getFormattedAddress(){
        throw new Exception('Not implemented');
    }


    /**
     * Creates a new instance of KlinkInstitutiondetails
     * @param string $id the Insitution ID, must be an alphanumeric non empty (or non null) string
     * @param string $name the name of the institution, must be a non empty string
     * @param string $type The organization type according to the classification of schema.org
     * @param string $joinedDate (optional) the date on which the institution has joined the Klink using the RFC3339 format, if null is given the current date and time will be used
     * @return KlinkInstitutionDetails
     * @throws InvalidArgumentException if some parameters are invalid
     */
    public static function create($id, $name,  $type = 'Organization',  $joinedDate = null){

        KlinkHelpers::is_valid_id( $id, 'id' );

        KlinkHelpers::is_string_and_not_empty( $name, 'name' );

        if( !is_null( $joinedDate ) ){
            KlinkHelpers::is_valid_date_string( $joinedDate, 'joinedDate' );
        }
        else {
            $joinedDate = KlinkHelpers::now();
        }

        $instance = new self();

        $instance->id = $id;

        $instance->name = $name;

        $instance->creationDate = $joinedDate;

        $instance->mail = null;

        $instance->phone = null;

        $instance->thumbnailURI = null;

        $instance->type = $type;

        $instance->url = null;

// id   string  true    {not blank}, {match: /^[a-zA-Z0-9-]+$/} The Object Id
// name    string  true    {not blank} The Institution Name
// type    string  true    {not blank} The Institution type, according to Schema.org
// email   string  true    {email address}, {not blank}    
// url string  true    {url}, {not blank}  
// phone   string  true    {not blank} 
// creationDate    string  true    {not blank} 
// thumbnailURI    string  true    {not blank}, {url}

        return $instance;

    }


}

/**
 * Describe an organization address
 * @package default
 */
final class Klink_Address
{
    private $street;
    private $country;
    private $locality;
    private $postalCode;

    /**
     * 
     */
    public function __construct( $street, $country, $locality, $postalCode ){

        $this->street = $street;
        $this->country = $country;
        $this->locality = $locality;
        $this->postalCode = $postalCode;

    }

    /**
     * The street address. For example, 1600 Amphitheatre Pkwy.
     * @return type
     */
    public function getStreet(){
        return $this->street;
    }

    /**
     * The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
     * @return type
     */
    public function getCountry(){
        return $this->country;
    }

    /**
     * The locality. For example, Mountain View.
     * @return type
     */
    public function getLocality(){
        return $this->locality;
    }

    /**
     * The postal code. For example, 94043.
     * @return type
     */
    public function getPostalCode(){
        return $this->postalCode;
    }

    
    // addressRegion   Text    The region. For example, CA.
    // postOfficeBoxNumber Text    The post office box number for PO box addresses.
    

    public function getGeoCoords()
    {
        //do something with the $street and $city
    }
}