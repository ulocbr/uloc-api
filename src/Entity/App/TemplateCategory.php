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
    protected $childrens;
    protected $parent;

    /**
     * TemplateCategory constructor.
     */
    public function __construct()
    {
        $this->templates = new ArrayCollection();
        $this->childrens = new ArrayCollection();
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

    /**
     * @return ArrayCollection|self
     */
    public function getChildrens()
    {
        return $this->childrens;
    }

    /**
     * @param ArrayCollection|self $childrens
     */
    public function addChildren(self $category): void
    {
        $this->childrens[] = $category;
    }

    public function removeChildren(self $category): void
    {
        if ($this->childrens->contains($category) ) {
            $this->childrens->removeElement($category);
            $category->setParent(null);
        }
    }

    /**
     * @return self|null
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * @param self|null $parent
     */
    public function setParent(?self $parent): void
    {
        $this->parent = $parent;
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
            'internal',
            'parent', ['id', 'code', 'name', 'internal']
        ];
        $representation
            ->setGroup('public')
            ->addProperties(
                $public
            )->build();
    }

}
