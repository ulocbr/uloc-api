<?php

namespace Uloc\ApiBundle\Tests;

use Uloc\ApiBundle\Entity\User\User;

class UserManagerTest extends AbstractFuncionalTest
{
    public function testNewUser()
    {
        $userManager = $this->container->get('uloc_api.manager.user_manager');

        $user = $userManager
            ->disablePersist()
            ->create('Tiago', 'tiagofelipe', '1234', true);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('tiagofelipe', $user->getUsername());
        $this->assertEquals('tiagofelipe', $user->getUsername());
        $this->assertEquals(true, $user->isActive());

    }
}