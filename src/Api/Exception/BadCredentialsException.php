<?php
namespace Uloc\ApiBundle\Api\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class BadCredentialsException
 * @package Uloc\ApiBundle\Api\Exception
 */
class BadCredentialsException extends AuthenticationException
{
    private $customMessage;
    /**
     * BadCredentialsException constructor.
     */
    public function __construct($message = 'Credenciais inválidas')
    {
        parent::__construct();
        $this->customMessage = $message;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return $this->customMessage;
    }
}