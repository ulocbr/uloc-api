<?php


namespace Uloc\ApiBundle\Repository;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BaseEntityRepository extends EntityRepository
{

    public function filterActive(QueryBuilder $query, $active, $queryCount = null)
    {
        if (gettype($active) === 'boolean') {
        } else {
            $active = (string)$active === '1' || (string)$active === 'true';
        }
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('a.active', $active));
        $query->addCriteria($criteria);
        if (null !== $queryCount) {
            $queryCount->addCriteria($criteria);
        }
    }

}