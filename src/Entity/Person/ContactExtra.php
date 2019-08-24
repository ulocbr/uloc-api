<?php

namespace Uloc\ApiBundle\Entity\Person;

/**
 * ContactExtra
 *
 */
class ContactExtra
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
    private $name;

    /**
     * @var string
     *
     */
    private $tag;

    /**
     * @var string
     *
     */
    private $value;

    /**
     * @var TypeContactExtraPurpose
     */
    private $purpose;

    /**
     * Muitos ContatosExtras tem Um Person.
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
     * Set tag
     *
     * @param string $tag
     *
     * @return static
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @return mixed
     */
    public function getPurposeArray()
    {
        /*if( null === $this->purpose ){
            return $this->otherPurpose;
        }*/
        return array("id" => $this->purpose->getId(), "name" => $this->purpose->getName());
    }

    /**
     * @param TypeEmailPurpose $purpose
     */
    public function setPurpose(TypeEmailPurpose $purpose)
    {
        $this->purpose = $purpose;
    }
}
