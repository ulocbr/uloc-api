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

class Template extends File
{

    protected $id;

    protected $code;

    protected $name;

    protected $subject;

    protected $description;

    protected $category;

    protected $template;

    /**
     * @var Template Versions
     */
    protected $email;
    protected $pdf;
    protected $csv;
    protected $excel;
    protected $txt;
    protected $printer;

    protected $pureText;

    protected $internal = false;

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
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return Template
     */
    public function getEmail(): Template
    {
        return $this->email;
    }

    /**
     * @param Template $email
     */
    public function setEmail(Template $email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @param mixed $pdf
     */
    public function setPdf($pdf): void
    {
        $this->pdf = $pdf;
    }

    /**
     * @return mixed
     */
    public function getCsv()
    {
        return $this->csv;
    }

    /**
     * @param mixed $csv
     */
    public function setCsv($csv): void
    {
        $this->csv = $csv;
    }

    /**
     * @return mixed
     */
    public function getExcel()
    {
        return $this->excel;
    }

    /**
     * @param mixed $excel
     */
    public function setExcel($excel): void
    {
        $this->excel = $excel;
    }

    /**
     * @return mixed
     */
    public function getTxt()
    {
        return $this->txt;
    }

    /**
     * @param mixed $txt
     */
    public function setTxt($txt): void
    {
        $this->txt = $txt;
    }

    /**
     * @return mixed
     */
    public function getPrinter()
    {
        return $this->printer;
    }

    /**
     * @param mixed $printer
     */
    public function setPrinter($printer): void
    {
        $this->printer = $printer;
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);
        $public = [
            'id',
            'code',
            'name',
            'subject',
            'description',
            'category' => ['id', 'name', 'type'],
            'template',
            'email',
            'pdf',
            'csv',
            'excel',
            'txt',
            'printer',
            'pureText',
            'versions',
            'internal',
        ];
        $representation
            ->setGroup('public')
            ->addProperties(
                $public
            )->build();
    }


}
