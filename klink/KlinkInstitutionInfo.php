<?php 

/**
* 
*/
class Klink_InstitutionInfo
{
	
	/**
     * Full name
     * @var string
     */
    public $name;

    /**
     * The institution address
     * @var Klink_Address
     */
    public $address;

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