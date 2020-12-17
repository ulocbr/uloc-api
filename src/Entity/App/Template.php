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

class Template extends FormEntity
{

    protected $id;

    protected $code;

    protected $name;

    protected $description;

    protected $category;

    protected $template;

    protected $pureText;

    /**
     * @var array
     */
    protected $versions;

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
     * @return TemplateCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param TemplateCategory $category
     */
    public function setCategory(TemplateCategory $category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getPureText()
    {
        return $this->pureText;
    }

    /**
     * @param mixed $pureText
     */
    public function setPureText($pureText): void
    {
        $this->pureText = $pureText;
    }

    /**
     * @return array
     */
    public function getVersions(): ?array
    {
        return $this->versions;
    }

    /**
     * @param array $versions
     */
    public function setVersions(?array $versions): void
    {
        $this->versions = $versions;
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);
        $public = [
            'id',
            'code',
            'name',
            'description',
            'category' => ['id', 'name', 'type'],
            'template',
            'pureText',
            'versions'
        ];
        $representation
            ->setGroup('public')
            ->addProperties(
                $public
            )->build();
    }


}
