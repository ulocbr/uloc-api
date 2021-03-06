<?php
/**
 * Este file é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o file LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity\App;

use Uloc\ApiBundle\Entity\FormEntity;

/**
 * Classe abstrata para fornecer os métodos e requisitos para armazenar um file no sistema
 *
 * @author Tiago Felipe
 * @version 0.0.1
 *
 */
abstract class File extends FormEntity
{

    protected $file;

    private $filename;

    private $fileUrl;

    private $fileVersions;

    private $fileOriginalFilename;

    private $fileMimeType;

    private $fileResolution;

    private $fileSize;

    function __construct()
    {
    }

    function __destruct()
    {
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    public function fileInfo($options){
        return pathinfo($this->file, $options);
    }

    public function fileName(){
        return $this->fileInfo(PATHINFO_FILENAME);
    }

    public function fileExtension(){
        return $this->fileInfo(PATHINFO_EXTENSION);
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param mixed $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    /**
     * @param mixed $fileUrl
     */
    public function setFileUrl($fileUrl): void
    {
        $this->fileUrl = $fileUrl;
    }

    /**
     * @return mixed
     */
    public function getFileVersions()
    {
        return $this->fileVersions;
    }

    /**
     * @param mixed $fileVersions
     */
    public function setFileVersions($fileVersions): void
    {
        $this->fileVersions = $fileVersions;
    }

    /**
     * @return mixed
     */
    public function getFileOriginalFilename()
    {
        return $this->fileOriginalFilename;
    }

    /**
     * @param mixed $fileOriginalFilename
     */
    public function setFileOriginalFilename($fileOriginalFilename): void
    {
        $this->fileOriginalFilename = $fileOriginalFilename;
    }

    /**
     * @return mixed
     */
    public function getFileMimeType()
    {
        return $this->fileMimeType;
    }

    /**
     * @param mixed $fileMimeType
     */
    public function setFileMimeType($fileMimeType): void
    {
        $this->fileMimeType = $fileMimeType;
    }

    /**
     * @return mixed
     */
    public function getFileResolution()
    {
        return $this->fileResolution;
    }

    /**
     * @param mixed $fileResolution
     */
    public function setFileResolution($fileResolution): void
    {
        $this->fileResolution = $fileResolution;
    }



}
