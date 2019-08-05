<?php

namespace Uloc\ApiBundle\Manager\Model;

interface CustomManagerInterface
{
    public function disableFlush();

    public function enableFlush();

    public function transactional(bool $value);

    public function beginTransaction();

    public function commit();

    public function rollBack();

    public function flush();

    // public function save();
    // public function autoSave(bool $value);

}