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
    protected $transactionalEnabled = true;

    /* @var ObjectManager $om */
    protected $om;

    /**
     * CustomManager constructor.
     * @param object $om ObjectManager para persistência
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
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

    public function flush()
    {
        if ($this->flushEnabled) {
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