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
