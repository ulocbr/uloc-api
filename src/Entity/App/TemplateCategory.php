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

use Doctrine\Common\Collections\ArrayCollection;
use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

class TemplateCategory extends FormEntity
{

	protected $id;

	protected $code;

	protected $name;

    protected $description;

    protected $type;

    protected $templates;

    protected $internal;

    /**
     * TemplateCategory constructor.
     */
    public function __construct()
    {
        $this->templates = new ArrayCollection();
    }


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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return ArrayCollection|Template
     */
    public function getTemplates(): ArrayCollection
    {
        return $this->templates;
    }

    /**
     * @param ArrayCollection|Template $templates
     */
    public function addTemplate(Template $template): void
    {
        $this->templates[] = $template;
    }

    /**
     * @return mixed
     */
    public function getInternal()
    {
        return $this->internal;
    }

    /**
     * @param mixed $internal
     */
    public function setInternal($internal): void
    {
        $this->internal = $internal;
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);
        $public = [
            'id',
            'code',
            'name',
            'description',
            'type',
            'internal'
        ];
        $representation
            ->setGroup('public')
            ->addProperties(
                $public
            )->build();
    }

}
