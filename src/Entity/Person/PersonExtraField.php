<?php

namespace Uloc\ApiBundle\Entity\Person;

use Uloc\ApiBundle\Entity\FormEntity;

/**
 * PersonExtraField
 *
 */
class PersonExtraField extends FormEntity
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
    private $value;

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
     * Muitos CamposExtras tem Um Person.
     */
    private $person;

    /**
     * Muitos CamposPreenchidos tem Um ExtraField.
     */
    private $extraField;

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     * @return static
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return ExtraField
     */
    public function getExtraField()
    {
        return $this->extraField;
    }

    /**
     * @param ExtraField $extraField
     * @return static
     */
    public function setExtraField(ExtraField $extraField)
    {
        $this->extraField = $extraField;
        return $this;
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
}
