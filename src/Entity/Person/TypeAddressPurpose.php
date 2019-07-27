<?php

namespace Uloc\ApiBundle\Entity\Person;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * TypeAddressPurpose
 *
 */
class TypeAddressPurpose extends TypePurpose
{

    const TIPO_RESIDENCIAL = 'system-1';
    const TIPO_COMERCIAL = 'system-2';

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
     * Um Finalidade tem Muitos Addresses
     */
    private $addresses;

    /**
     * TipoFinalidadeEndereco constructor.
     */
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
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
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param Address $endereco
     */
    public function addAddress(Address $address)
    {
        $this->addresses[] = $address;
    }

    /**
     * Remove endereco
     *
     * @param Address $endereco
     */
    public function removeEndereco(Address $endereco)
    {
        $this->addresses->removeElement($endereco);
    }
}
