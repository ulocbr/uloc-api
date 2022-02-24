<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity\App;

use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * @author Tiago Felipe
 * @version 0.0.1
 *
 */
class Variable extends FormEntity
{

    protected $id;

    protected $name;

    protected $value;

    protected $description;

    protected $internal = false;

    /**
     * Parametros para chamar alguma funcao especifica para tratamento da variavel.
     */
    protected $callback;

    protected $fake;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param mixed $callback
     */
    public function setCallback($callback): void
    {
        $this->callback = $callback;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getInternal(): ?bool
    {
        return $this->internal;
    }

    /**
     * @param mixed $internal
     */
    public function setInternal(?bool $internal): void
    {
        $this->internal = $internal;
    }

    /**
     * @return mixed
     */
    public function getFake()
    {
        return $this->fake;
    }

    /**
     * @param mixed $fake
     */
    public function setFake($fake): void
    {
        $this->fake = $fake;
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);
        $public = [
            'id',
            'name',
            'value',
            'description',
            'internal',
            'callback',
        ];
        $representation
            ->setGroup('public')
            ->addProperties(
                $public
            )->build();
    }

}
