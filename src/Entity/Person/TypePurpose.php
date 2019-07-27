<?php

namespace Uloc\ApiBundle\Entity\Person;

/**
 * TypePurpose
 *
 */
abstract class TypePurpose
{

    /**
     * @var string
     *
     */
    private $code;

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

}
