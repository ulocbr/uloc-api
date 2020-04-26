<?php

namespace Uloc\ApiBundle\Entity\Person;

use Doctrine\Common\Collections\ArrayCollection;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * TypePersonDocument
 *
 */
class TypePersonDocument extends TypePurpose
{

    const TIPO_BRAZIL_RG = 'system-1';
    const TIPO_PASSAPORTE = 'system-2';

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
     * Um Tipo tem Muitos Identifiers
     */
    private $identifiers;

    public function __construct()
    {
        $this->identifiers = new ArrayCollection();
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
    public function getIdentifiers()
    {
        return $this->identifiers;
    }

    /**
     * Add identifier
     *
     * @param PersonDocument $identifier
     *
     * @return static
     */
    public function addIdentifier(PersonDocument $identifier)
    {
        $this->identifiers[] = $identifier;

        return $this;
    }

    /**
     * Remove identifier
     *
     * @param PersonDocument $identifier
     */
    public function removeIdentifier(PersonDocument $identifier)
    {
        $this->identifiers->removeElement($identifier);
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);

        $public = [
            'id',
            'name'
        ];

        $representation
            ->setGroup('public')->addProperties($public)
            ->setGroup('admin')->addProperties($public)->build();
    }
}
