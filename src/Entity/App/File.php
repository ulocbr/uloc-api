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


/**
 * Classe abstrata para fornecer os métodos e requisitos para armazenar um file no sistema
 *
 * @author Tiago Felipe
 * @version 0.0.1
 *
 */
abstract class File
{

    protected $file;

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

}
