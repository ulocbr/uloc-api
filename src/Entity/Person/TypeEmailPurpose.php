<?php

namespace Uloc\ApiBundle\Entity\Person;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * TypeEmailPurpose
 *
 */
class TypeEmailPurpose extends TypePurpose
{

    const TIPO_PESSOAL = 'system-1'; // TODO: Ajustar
    const TIPO_TRABALHO = 'system-2';

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
     * Um TipoFinalidade tem Muitos Emails
     */
    private $emails;

    public function __construct()
    {
        $this->emails = new ArrayCollection();
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
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @param ContactEmail $email
     */
    public function addEmail(ContactEmail $email)
    {
        $this->emails[] = $email;
    }

    /**
     * Remove email
     *
     * @param ContactEmail $email
     */
    public function removeEmail(ContactEmail $email)
    {
        $this->emails->removeElement($email);
    }
}
