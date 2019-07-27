<?php

namespace Uloc\ApiBundle\Entity\Person;

/**
 * Address
 *
 */
class Address
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var string
     *
     * !Assert\NotBlank(message="Endereço invalido")
     */
    private $address;

    /**
     * @var string
     *
     */
    private $complement;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $district;

    /**
     * @var string
     */
    private $districtId;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     *
     */
    private $city;

    /**
     * @var string
     */
    private $cityId;

    /**
     * @var string
     *
     */
    private $state;

    /**
     * @var string
     */
    private $stateId;

    /**
     * @var string
     *
     */
    private $otherPurpose;

    /**
     * @var bool
     *
     */
    private $default;

    /**
     * @var string
     *
     */
    private $latitude;

    /**
     * @var string
     *
     */
    private $longitude;

    /**
     */
    private $purpose;

    /**
     * Muitos Enderecos tem Um Person.
     */
    private $person;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
    }

    /**
     * @return mixed
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param TypeAddressPurpose $purpose
     */
    public function setPurpose(TypeAddressPurpose $purpose)
    {
        $this->purpose = $purpose;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return static
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set complement
     *
     * @param string $complement
     *
     * @return static
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get complement
     *
     * @return string
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return static
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set district
     *
     * @param string $district
     *
     * @return static
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set zip
     *
     * @param integer $zip
     *
     * @return static
     */
    public function setZip($zip)
    {
        //$this->zip = preg_replace('/\D/', '$1', $zip);
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return int
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return static
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set otherPurpose
     *
     * @param string $otherPurpose
     *
     * @return static
     */
    public function setOtherPurpose($otherPurpose)
    {
        $this->otherPurpose = $otherPurpose;

        return $this;
    }

    /**
     * Get otherPurpose
     *
     * @return string
     */
    public function getOtherPurpose()
    {
        return $this->otherPurpose;
    }

    /**
     * Set default
     *
     * @param boolean $default
     *
     * @return static
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return bool
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return static
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return static
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function getDistrictId(): string
    {
        return $this->districtId;
    }

    /**
     * @param string $districtId
     */
    public function setDistrictId(string $districtId): void
    {
        $this->districtId = $districtId;
    }

    /**
     * @return string
     */
    public function getCityId(): string
    {
        return $this->cityId;
    }

    /**
     * @param string $cityId
     */
    public function setCityId(string $cityId): void
    {
        $this->cityId = $cityId;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getStateId(): string
    {
        return $this->stateId;
    }

    /**
     * @param string $stateId
     */
    public function setStateId(string $stateId): void
    {
        $this->stateId = $stateId;
    }
}
