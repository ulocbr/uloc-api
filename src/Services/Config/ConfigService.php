<?php
namespace Uloc\ApiBundle\Services\Config;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Uloc\ApiBundle\Entity\App\GlobalConfig;

class ConfigService implements ConfigServiceInterface
{

    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function getAppConfig($name, $namespace = 'global', $register = null, $expires = 86400)
    {
        /*$cache = new FilesystemAdapter($namespace); // TODO: Mudar tipo de cache para mais performático
        return $cache->get($name, function (ItemInterface $item) use ($name, $register, $expires) {
            $item->expiresAfter($expires);

            if (null !== $register && is_callable($register)) {
                $computedValue = $register();
            } else {
                /1* @var $config \Uloc\ApiBundle\Entity\App\GlobalConfig *1/
                $config = $this->om->getRepository(GlobalConfig::class)->findOneByName($name);
                if (!$config) {
                    return null;
                }
                $computedValue = $config->getValue();
            }

            return $computedValue;
        });*/

        $config = $this->om->getRepository(GlobalConfig::class)->findOneByName($name);
        if (!$config) {
            return null;
        }
        return $computedValue = $config->getValue();
    }

    public function getConfigsByPermission($permission)
    {
        $config = $this->om->getRepository(GlobalConfig::class)->findBy(['permission' => $permission]);
        if (!$config) {
            return null;
        }

        $array = [];
        foreach ($config as $c) {
            $array[$c->getName()] = $c->getValue();
        }
        return $array;
    }

}