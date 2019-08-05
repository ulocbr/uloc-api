<?php
/**
 * Created by PhpStorm.
 * User: Tiago
 * Date: 25/10/2018
 * Time: 16:41
 */

namespace Uloc\ApiBundle\Manager;


interface PersonManagerInterface
{

    public function create(string $name, int $type = 1, bool $active = true);

    public function find(int $id);

    public function update();

    public function remove();

    public function list();


}