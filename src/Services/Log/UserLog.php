<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Services\Log;

use Doctrine\ORM\EntityManagerInterface;
use Uloc\ApiBundle\Model\UserInterface;

/**
 * Serviço responsável pelo armazenamento do Logger de todas as ações dos usuários
 */
class UserLog implements LogInterface
{

    private $em;
    /**
     * UserLog constructor.
     * @param object $em    EntityManager para persistência do log
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function log($message, $entity, $action, $context, $oldObject = null, $newObject = null, $ip = null){

    }

    public function emergency($message, array $context = array())
    {
        // TODO: Implement emergency() method.
    }

    public function alert($message, array $context = array())
    {
        // TODO: Implement alert() method.
    }

    public function critical($message, array $context = array())
    {
        // TODO: Implement critical() method.
    }

    public function error($message, array $context = array())
    {
        // TODO: Implement error() method.
    }

    public function warning($message, array $context = array())
    {
        // TODO: Implement warning() method.
    }

    public function notice($message, array $context = array())
    {
        // TODO: Implement notice() method.
    }

    public function info($message, array $context = array())
    {
        // TODO: Implement info() method.
    }

    public function debug($message, array $context = array())
    {
        // TODO: Implement debug() method.
    }


}