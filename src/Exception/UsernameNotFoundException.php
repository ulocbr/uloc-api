<?php

namespace Uloc\ApiBundle\Exception;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException as AbstractUsernameNotFoundException;

class UsernameNotFoundException extends AbstractUsernameNotFoundException
{

    public function getMessageKey()
    {
        return 'Usuário não encontrado.';
    }

}
