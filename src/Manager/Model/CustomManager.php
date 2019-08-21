<?php
/**
 * Created by PhpStorm.
 * User: Tiago
 * Date: 28/03/2019
 * Time: 14:13
 */

namespace Uloc\ApiBundle\Manager\Model;

use Doctrine\Common\Persistence\ObjectManager;

class CustomManager implements CustomManagerInterface
{
    protected $flushEnabled = true;
    protected $persistEnabled = true;
    protected $transactionalEnabled = true;

    /* @var ObjectManager $om */
    protected $om;

    /**
     * CustomManager constructor.
     * @param ObjectManager $om para persistência
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function disablePersist()
    {
        $this->persistEnabled = false;
        return $this;
    }

    public function enablePersist()
    {
        $this->persistEnabled = true;
        return $this;
    }

    public function disableFlush()
    {
        $this->flushEnabled = false;
        return $this;
    }

    public function enableFlush()
    {
        $this->flushEnabled = true;
        return $this;
    }

    public function transactional(bool $value)
    {
        $this->transactionalEnabled = $value;
        return $this;
    }

    public function persist($obj)
    {
        if ($this->persistEnabled) {
            $this->om->persist($obj);
        }
    }

    public function flush()
    {
        if ($this->flushEnabled && $this->persistEnabled) {
            $this->om->flush();
        }
    }

    public function beginTransaction()
    {
        if (!$this->transactionalEnabled) {
            return;
        }
        if (method_exists('getConnection', $this->om)) {
            $this->om->getConnection()->beginTransaction();
        }
    }

    public function commit()
    {
        if (!$this->transactionalEnabled) {
            return;
        }
        if (method_exists('getConnection', $this->om)) {
            $this->om->getConnection()->commit();
        }
    }

    public function rollBack()
    {
        if (!$this->transactionalEnabled) {
            return;
        }
        if (method_exists('getConnection', $this->om)) {
            $this->om->getConnection()->rollBack();
        }
    }

}