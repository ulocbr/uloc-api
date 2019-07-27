<?php

namespace Uloc\ApiBundle\Entity\Person;

/**
 * ContactEmail
 *
 */
class ContactEmail
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
     * !Assert\NotBlank(message="Necessario informar um email")
     * !Assert\Email(message="Email invalido")
     */
    private $email;

    /**
     * @var bool
     *
     */
    private $valid;

    /**
     * @var bool
     *
     */
    private $default;

    /**
     * Muitos Emails tem Um Person.
     */
    private $person;

    /**
     * Muitos Emails tem Um PurposeEmail.
     */
    private $purpose;

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
    public function setPerson(?Person $person)
    {
        $this->person = $person;
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
        if( null === $this->purpose ){
            return $this->otherPurpose;
        }
        return array("id" => $this->purpose->getId(), "name" => $this->purpose->getName());
    }

    /**
     * @param TypeEmailPurpose $purpose
     */
    public function setPurpose(TypeEmailPurpose $purpose)
    {
        $this->purpose = $purpose;
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
     * Set email
     *
     * @param string $email
     *
     * @return static
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     *
     * @return static
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return bool
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set default
     *
     * @param boolean $default
     *
     * @return static
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
     * @return string
     */
    public function getOtherPurpose(): string
    {
        return $this->otherPurpose;
    }

    /**
     * @param string $otherPurpose
     */
    public function setOtherPurpose(string $otherPurpose): void
    {
        $this->otherPurpose = $otherPurpose;
    }
}
