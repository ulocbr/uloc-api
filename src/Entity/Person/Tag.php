<?php

namespace Uloc\ApiBundle\Entity\Person;

use Doctrine\Common\Collections\ArrayCollection;
use Uloc\ApiBundle\Entity\App\TagInterface;
use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * Tag
 *
 */
class Tag extends FormEntity implements TagInterface
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
    private $code;

    /**
     * @var string
     *
     */
    private $name;

    /**
     * @var string
     *
     */
    private $description;

    /**
     * @var string
     *
     */
    private $color;

    /**
     * @var boolean
     *
     */
    private $internal = false;

    /**
     * Muitos Tags tem Um Person.
     */
    private $persons;

    /**
     * Muitos TagsCriadas tem Um Person.
     */
    private $creator;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param Person $creator
     */
    public function setCreator(Person $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->persons;
    }

    /**
     * @param Person $persons
     */
    public function addPerson(Person $person)
    {
        $this->persons[] = $person;
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
     * @return Tag
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
     * Set description
     *
     * @param string $description
     *
     * @return Tag
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Remove person
     *
     * @param \Uloc\ApiBundle\Entity\Person\Person $person
     */
    public function removePerson(\Uloc\ApiBundle\Entity\Person\Person $person)
    {
        $this->persons->removeElement($person);
    }

    /**
     * Get persons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return bool
     */
    public function isInternal(): bool
    {
        return $this->internal;
    }

    /**
     * @param bool $internal
     */
    public function setInternal(bool $internal): void
    {
        $this->internal = $internal;
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        // TODO: Implement loadApiRepresentation() method.
    }
}
