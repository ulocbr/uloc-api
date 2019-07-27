<?php

namespace Uloc\ApiBundle\Entity\Person;

/**
 * ContactPhone
 *
 * #CustomAssert\PhoneNumber(groups={"Default"})
 */
class ContactPhone
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var string
     *
     */
    private $otherPurpose;

    /**
     * @var string
     *
     */
    private $areaCode;

    /**
     * @var string
     *
     */
    private $phoneNumber;

    /**
     * @var bool
     *
     */
    private $cellphone;

    /**
     * @var bool
     *
     */
    private $default;

    /**
     * Armazena em objeto os aplicativos de mensagem instantânea compatíveis com este número de phoneNumber.
     *
     * @var \stdClass
     */
    private $im;

    /**
     * Muitos PhoneNumbers tem Um Person.
     */
    private $person;

    /**
     * Muitos PhoneNumbers tem Um TypePhonePurpose.
     */
    private $purpose;

    public function __construct()
    {
        $this->areaCode = '55'; //Brasil defaults
    }

    /**
     * @return mixed
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param TypePhonePurpose $purpose
     */
    public function setPurpose(TypePhonePurpose $purpose)
    {
        $this->purpose = $purpose;
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set otherPurpose
     *
     * @param string $otherPurpose
     *
     * @return ContatoPhoneNumber
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
     * Set areaCode
     *
     * @param string $areaCode
     *
     * @return ContatoPhoneNumber
     */
    public function setAreaCode($areaCode)
    {
        $this->areaCode = $areaCode;

        return $this;
    }

    /**
     * Get areaCode
     *
     * @return string
     */
    public function getAreaCode()
    {
        return $this->areaCode;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return ContatoPhoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = preg_replace('/\D/', '$1', $phoneNumber);

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set cellphone
     *
     * @param boolean $cellphone
     *
     * @return ContatoPhoneNumber
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get cellphone
     *
     * @return bool
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * Set default
     *
     * @param boolean $default
     *
     * @return ContatoPhoneNumber
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
     * Set im
     *
     * @param \stdClass $im
     *
     * @return ContatoPhoneNumber
     */
    public function setIm($im)
    {
        $this->im = $im;

        return $this;
    }

    /**
     * Get im
     *
     * @return \stdClass
     */
    public function getIm()
    {
        return $this->im;
    }

    public function splitBrazilianDDD()
    {
        $phoneNumber = preg_replace('/\D/', '$1', $this->phoneNumber);
        $ddd = substr($phoneNumber, 0, 2);
        $phoneNumber = substr($phoneNumber, 2, strlen($phoneNumber) - 2);
        return array($ddd, $phoneNumber);
    }
}
