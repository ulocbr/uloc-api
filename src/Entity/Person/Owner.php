<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity\Person;

/**
 * Uma person pode ter um proprietário (account owner), este proprietário pode mudar
 * Esta classe armazena os proprietários de uma person, porém somente um pode estar enabled, ou seja
 * somente um pode ser o accouont owner/manager
 *
 */
class Owner
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var \DateTime
     *
     */
    private $date;

    /**
     * @var string
     *
     */
    private $descriptionAssignment;

    /**
     * @var bool
     *
     */
    private $enabled;

    /**
     * Muitos Owners tem Um Person.
     */
    private $person;

    /**
     * Muitos PersonsGerenciadas tem Um Person.
     */
    private $owner;

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param Person $owner
     */
    public function setOwner(Person $owner)
    {
        $this->owner = $owner;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return static
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set descriptionAssignment
     *
     * @param string $descriptionAssignment
     *
     * @return static
     */
    public function setDescriptionAssignment($descriptionAssignment)
    {
        $this->descriptionAssignment = $descriptionAssignment;

        return $this;
    }

    /**
     * Get descriptionAssignment
     *
     * @return string
     */
    public function getDescriptionAssignment()
    {
        return $this->descriptionAssignment;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
