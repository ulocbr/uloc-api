<?php

namespace Uloc\ApiBundle\Tests;


use Uloc\ApiBundle\Services\Template\TemplateService;

class TemlateServiceTest extends AbstractFuncionalTest
{
    public function testNewUser()
    {
        $service = $this->container->get('uloc_api.template.default');

        $this->assertInstanceOf(TemplateService::class, $service);

    }
}