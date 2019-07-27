<?php

namespace Uloc\ApiBundle\Entity\Person;

/**
 * PersonDocument
 *
 */
class PersonDocument
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
    private $identifier;

    /**
     * @var string
     *
     */
    private $agentDispatcher;

    /**
     * Muitos Identifieres tem Um TypePersonDocument.
     */
    private $type;

    /**
     * Muitos Documents tem Um Person.
     */
    private $person;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param TypePersonDocument $type
     */
    public function setType(TypePersonDocument $type)
    {
        $this->type = $type;
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
     * Set identifier
     *
     * @param string $identifier
     *
     * @return static
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set agentDispatcher
     *
     * @param string $agentDispatcher
     *
     * @return static
     */
    public function setAgentDispatcher($agentDispatcher)
    {
        $this->agentDispatcher = $agentDispatcher;

        return $this;
    }

    /**
     * Get agentDispatcher
     *
     * @return string
     */
    public function getAgentDispatcher()
    {
        return $this->agentDispatcher;
    }
}
