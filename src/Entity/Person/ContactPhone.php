<?php

namespace Uloc\ApiBundle\Entity\Person;

use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * ContactPhone
 *
 * #CustomAssert\PhoneNumber(groups={"Default"})
 */
class ContactPhone extends FormEntity
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
        parent::__construct();
        $this->setActive(true);
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
     * @return self
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
     * @return self
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
     * @return self
     */
    public function setPhoneNumber($phoneNumber)
    {
        //$this->phoneNumber = preg_replace('/\D/', '$1', $phoneNumber);
        $this->phoneNumber = preg_replace('/[^0-9+]/', '$1', $phoneNumber);

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
     * @return self
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
     * @return self
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
     * @return self
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

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);

        $public = [
            'id',
            'otherPurpose',
            'areaCode',
            'phoneNumber',
            'cellphone',
            'default',
            'im',
            'purpose' => ['id', 'code', 'name'],
        ];

        $representation
            ->setGroup('public')->addProperties($public)
            ->setGroup('admin')->addProperties($public)->build();
    }
}
