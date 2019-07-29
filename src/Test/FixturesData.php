<?php

namespace Uloc\Bundle\AppBundle\Test;

/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

use \Doctrine\ORM\EntityManager;
use Uloc\ApiBundle\Entity\Person\ExtraField;
use Uloc\ApiBundle\Entity\Person\RegistrationOrigin;
use Uloc\ApiBundle\Entity\Person\TypeAddressPurpose;
use Uloc\ApiBundle\Entity\Person\TypeEmailPurpose;
use Uloc\ApiBundle\Entity\Person\TypePhonePurpose;

/**
 * Wrapper para ajudar nos testes preenchendo dados necessários para serem utilizados nos demais casos de teste
 * TODO: Concluir e utilizar
 */
class FixturesData
{
    private $em;
    public static $defaultIds;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public static function purge(){
        self::$defaultIds = array();
    }

    public function createTipoFinalidadeEndereco($name)
    {
        $o = new TypeAddressPurpose();
        $o->setName($name);
        $o->setCode(strval(rand(0000, 9999)));
        $this->persist($o);
        self::addDefaultIds('tipoFinalidadeEndereco', $o->getId());
        return $o;
    }

    /**
     * Módulo Contato
     */

    public function createTipoFinalidadeEmail($name)
    {
        $o = new TypeEmailPurpose();
        $o->setName($name);
        $o->setCode(strval(rand(0000, 9999)));
        $this->persist($o);
        self::addDefaultIds('tipoFinalidadeEmail', $o->getId());
        return $o;
    }

    public function createTipoFinalidadeTelefone($name)
    {
        $o = new TypePhonePurpose();
        $o->setCode(strval(rand(0000, 9999)));
        $o->setName($name);
        $this->persist($o);
        self::addDefaultIds('tipoFinalidadeTelefone', $o->getId());
        return $o;
    }

    /**
     * Módulo Pessoa
     */
    public function createCampoExtraPessoa($code, $name, $description = 'Campo Extra Exemplo', $required = false)
    {
        $o = new ExtraField();
        $o->setCode($code);
        $o->setName($name);
        $o->setDescription($description);
        $o->setRequired($required);
        $this->persist($o);
        self::addDefaultIds('pessoaCampoExtra', $o->getId());
        return $o;
    }

    public function createOrigemCadastroPessoa($name)
    {
        $o = new RegistrationOrigin();
        $o->setCode(strval(rand(0000, 9999)));
        $o->setName($name);
        $this->persist($o);
        self::addDefaultIds('origemCadastroPesoa', $o->getId());
        return $o;
    }

    public static function addDefaultIds($name, $val)
    {
        if (isset(self::$defaultIds[$name])) {
            if (is_array(self::$defaultIds[$name])) {
                array_push(self::$defaultIds[$name], $val);
            } else {
                self::$defaultIds[$name] = array($val);
            }
        } else {
            self::$defaultIds[$name] = array($val);
        }
    }

    private function persist($o)
    {
        $this->em->persist($o);
        $this->em->flush();
        $this->em->getUnitOfWork()->clear(); //Limpa a unidade de trabalho do doctrine
        return $o;
    }

}