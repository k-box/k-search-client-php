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
     * The K-Link Institution Identifier
     * @var string
     */	
    public $id;


	/**
     * The Institution Name
     * @var string
     */
    public $name;

    /**
     * The institution e-mail address for contact purposes
     * @var string
     */
    public $email;

    /**
     * The institution telephone number
     * @var string
     */
    public $phone;

    /**
     * The type of the institution according to schema.org
     * @var string
     */
    public $type;

    /**
     * The institution address
     * @var string
     */
    public $address;

    public $addressStreet;

    public $addressCountry;

    public $addressLocality;

    public $addressZip;

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

    /**
     * ...
     * @var string
     */
    public $url;

    /**
     * @internal
     */
    function __construct(){

    }


    /**
     * Returns the address of the institution.
     * 
     * @return Klink_Address
     */
    public function getKlinkAddress(){

        return new Klink_Address(
            $this->address,
            $this->addressStreet, 
            $this->addressCountry, 
            $this->addressLocality, 
            $this->addressZip);

    }

    /**
     * Returns the address of the institution.
     * 
     * @return string the formatted address
     */
    public function getAddress(){

        return empty($this->address) ? $this->getKlinkAddress()->__toString() : $this->address;

    }

    /**
     * Set the address of the institution
     *
     * @param mixed 
     * @return KlinkInstitutionDetails
     */
    public function setAddress($address){

        if($address instanceof Klink_Address){
            $this->addressStreet = $address->getStreet();
            $this->addressCountry = $address->getCountry();
            $this->addressLocality = $address->getLocality();
            $this->addressZip = $address->getPostalCode();
            $this->address = $address->getAddress();

            return $this;
        }

        $this->address = $address;

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

    /**
     * @param string $type
     * @return $this
     */
    public function setType( $type )
    {
        $this->type = $type;
        return $this;
    }

    /**
     * The name of the institution
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * The institution telephone number
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phone;
    }

    /**
     * @param string $number
     */
    public function setPhoneNumber($number)
    {
        KlinkHelpers::is_valid_phonenumber( $number, 'phone number' );

        $this->phone = $number;
    }

    /**
     * The institution e-mail address for contact purposes
     * @return string
     */
    public function getMail()
    {
        return $this->email;
    }

    /**
     * @param string $mail
     */
    public function setMail( $mail )
    {
        KlinkHelpers::is_valid_mail( $mail, 'mail' );

        $this->email = $mail;

        return $this;
    }

    /**
     * The institution ID
     * @return string
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

    /**
     * @param string $thumbnailURI
     */
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

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        KlinkHelpers::is_valid_url( $url, 'url' );

        $this->url = $url;
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

        $instance->email = null;

        $instance->phone = null;

        $instance->thumbnailURI = null;

        $instance->type = $type;

        $instance->url = null;

        return $instance;

    }


}

/**
 * Describe an organization address
 * @package default
 */
final class Klink_Address
{
    private $address;
    private $street;
    private $country;
    private $locality;
    private $postalCode;

    /**
     * Klink_Address constructor.
     *
     * @param $address string     The complete address of the institution
     * @param $street string      The street part of the address
     * @param $country string     The city part of the address
     * @param $locality string    The locality part of the address
     * @param $postalCode string  The postal code of the address
     */
    public function __construct($address, $street, $country, $locality, $postalCode ){

        $this->address = $address;
        $this->street = $street;
        $this->country = $country;
        $this->locality = $locality;
        $this->postalCode = $postalCode;

    }

    /**
     * Returns a full address.
     *
     * Example: "1600 Amphitheatre Pkwy 34 Building 34A room 2. Mountain View, 10092 - California"
     *
     * @return string
     */
    public function getAddress(){
        return $this->address;
    }

    /**
     * The street address. For example, 1600 Amphitheatre Pkwy.
     * @return string
     */
    public function getStreet(){
        return $this->street;
    }

    /**
     * The country. For example, USA. You can also provide the two-letter ISO 3166-1 alpha-2 country code.
     * @return string
     */
    public function getCountry(){
        return $this->country;
    }

    /**
     * The locality. For example, Mountain View.
     * @return string
     */
    public function getLocality(){
        return $this->locality;
    }

    /**
     * The postal code. For example, 94043.
     * @return string
     */
    public function getPostalCode(){
        return $this->postalCode;
    }

    public function __toString(){

        if(!empty($this->address)){
            return $this->address;
        }

        return trim(implode(" ",[
             $this->getStreet(),
             $this->getPostalCode() . ' ' . $this->getLocality() . '.',
             $this->getCountry()
         ]));
    }
}
