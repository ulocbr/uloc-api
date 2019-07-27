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

    public function log(UserInterface $user, $mensagem, $entidade, $acao, $contexto, $oldObject, $newObject){

    }

}