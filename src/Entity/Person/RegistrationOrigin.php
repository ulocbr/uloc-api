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

use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * Classe responsável por armazenar a origem do cadastro de uma Person
 *
 */
class RegistrationOrigin extends TypePurpose
{

    const TIPO_INTERNO = 'system-1';
    const TIPO_SITE = 'system-2';

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
     * @var string
     *
     */
    private $description;

    /**
     * @var \stdClass
     *
     */
    private $extra;

    /**
     * Um OrigemCadastro tem Muitos Persons
     */
    private $persons;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return mixed
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * @param Person $person
     */
    public function addPerson(Person $person)
    {
        $this->persons[] = $person;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return static
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
     * Set extra
     *
     * @param \stdClass $extra
     *
     * @return static
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return \stdClass
     */
    public function getExtra()
    {
        return $this->extra;
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

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        // TODO: Implement loadApiRepresentation() method.
    }
}
