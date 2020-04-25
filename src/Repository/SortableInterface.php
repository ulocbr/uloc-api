<?php


namespace App\Repository;


interface SortableInterface
{

    public function findAllSimple(int $limit = 100, int $offset = 0, $sortBy = null, $sortDesc = null, array $filters = null, $active = true, $hideDeleted = true);
}