<?php
/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 19/12/17
 * Time: 15:18
 */

namespace Uloc\ApiBundle\Services\FileUpload;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    private $destinoPublic;
    private $destinoPrivate;

    public function __construct($destinoPublic, $destinoPrivate)
    {
        $this->destinoPublic = $destinoPublic;
        $this->destinoPrivate = $destinoPrivate;
    }

    public function upload(UploadedFile $file, $directory, $private = false, $forceName = null)
    {

        if ($forceName !== null) {
            $name = $forceName . '.' . $this->translateExtension($file->guessExtension());
        } else {
            $name = md5(uniqid()) . '.' . $this->translateExtension($file->guessExtension());
        }

        $destino = $private ? $this->getDestinoPrivate() : $this->getDestino();

        // TODO: Verificar se arquivo existe

        $file->move($destino . '/' . $directory, $name);

        return $name;
    }

    public function getDestino()
    {
        return $this->destinoPublic;
    }

    public function getDestinoPrivate()
    {
        return $this->destinoPrivate;
    }

    public function translateExtension($extension)
    {
        switch (strtolower($extension)) {
            case 'jpeg':
                return 'jpg'; // most popular / short extension
            default:
                return strtolower($extension);
        }
    }

}