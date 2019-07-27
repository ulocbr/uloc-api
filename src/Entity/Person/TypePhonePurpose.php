<?php

namespace Uloc\ApiBundle\Entity\Person;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * TypePhonePurpose
 *
 */
class TypePhonePurpose extends TypePurpose
{
    const TIPO_PESSOAL = 'system-1'; // TODO: Ajustar
    const TIPO_TRABALHO = 'system-2';
    const TIPO_OUTROS = 'system-3';

    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var string
     *
     */
    private $name;

    /**
     * Um TipoFinalidade tem Muitos PhoneNumbers
     */
    private $phoneNumbers;

    public function __construct()
    {
        $this->phoneNumbers = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * @param ContactPhone $telefone
     */
    public function addPhoneNumber(ContactPhone $phone)
    {
        $this->phoneNumbers[] = $phone;
    }

    /**
     * Remove phone
     *
     * @param ContactPhone $phone
     */
    public function removePhoneNumber(ContactPhone $phone)
    {
        $this->phoneNumbers->removeElement($phone);
    }
}
