<?php

namespace Uloc\ApiBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * Class BaseRepository
 * @package UlocApiBundle\Repository
 */
class BaseRepository extends EntityRepository
{

    /*public function __construct(ManagerRegistry $registry, $entity = null)
    {
        parent::__construct($registry, $entity);
    }*/

    static $defaultSearch = 'name';

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

    public function defineSort(QueryBuilder $qb, $sortBy = 'id', $sortDesc = false, array $sortByPossibles = null)
    {
        if (!is_bool($sortDesc)) {
            $sortDesc = $sortDesc === 'true' || $sortDesc === '1';
        }

        if (empty($sortByPossibles)) {
            $sortByPossibles = [
                'id' => 'id',
            ];
        }

        if (in_array($sortBy, array_keys($sortByPossibles))) {
            $qb->orderBy($sortByPossibles[$sortBy], $sortDesc ? Criteria::DESC : Criteria::ASC);
        } else {
            // Ordering default
            // $qb->orderBy('id', 'DESC');
        }
    }

    public function defineFilterFormEntity($alias, QueryBuilder $query, QueryBuilder $queryCount, array $filters)
    {
        if (isset($filters['active'])) {
            $active = explode(',', $filters['active']);
            foreach ($active as $key => $sit) {
                if (!is_bool($sit)) {
                    $active[$key] = $sit === 'true' || $sit === '1';
                }
            }
            $activeCriteria = Criteria::create()
                ->where(
                    Criteria::expr()->in($alias . '.active', $active)
                );
            $query->addCriteria($activeCriteria);
            $queryCount->addCriteria($activeCriteria);
        }
    }

    public function processResult(Query $query, QueryBuilder $queryCount)
    {
        return [
            'result' => $query->getArrayResult(),
            'total' => $queryCount->getQuery()->getSingleScalarResult()
        ];
    }

    public function findAllSimpleBasic(
        $class,
        array $sortByPossibles,
        int $limit = 100,
        int $offset = 0,
        $sortBy = null,
        $sortDesc = null,
        array $filters = null,
        $onlyActive = false,
        $hideDeleted = true,
        $searchCriteria = null,
        $hydrationMode = \Doctrine\ORM\Query::HYDRATE_ARRAY,
        $defaultSelect = null,
        $joins = null
    )
    {
        $defaultAlias = 'a';
        $query = $this->getEntityManager()->createQueryBuilder();
        if (!$defaultSelect) {
            $query->select("
                $defaultAlias
            ");
        } else {
            $query->select($defaultSelect);
        }

        $query->from($class, $defaultAlias);

        if (null !== $joins && is_callable($joins)) {
            $joins($query);
        }

        $queryCount = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(1) total')
            ->from($class, $defaultAlias);

        if (empty($sortByPossibles)) {
            $sortByPossibles = [
                'id' => $defaultAlias . '.id'
            ];
        }

        $this->defineSort($query, $sortBy, $sortDesc, $sortByPossibles);
        $this->defineFilterFormEntity($defaultAlias, $query, $queryCount, $filters);

        if (isset($filters['search'])) {
            if (empty($searchCriteria)) {
                $searchCriteria = Criteria::create()->where(
                    Criteria::expr()->contains($defaultAlias . '.' . self::$defaultSearch, $filters['search'])
                );
            }
            $query->addCriteria($searchCriteria);
            $queryCount->addCriteria($searchCriteria);
        }

        $query = $query->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setHydrationMode($hydrationMode);

        return $this->processResult($query, $queryCount);
    }

    public function findAllSimple(int $limit = 100, int $offset = 0, $sortBy = null, $sortDesc = null, array $filters = null, $onlyActive = false, $hideDeleted = true)
    {
        $sortByPossibles = [
            'id' => 'a.id',
            'active' => 'a.active',
        ];
        return $this->findAllSimpleBasic(
            self::getClassName(),
            $sortByPossibles,
            $limit,
            $offset,
            $sortBy,
            $sortDesc,
            $filters,
            $onlyActive,
            $hideDeleted,
            null,
            \Doctrine\ORM\Query::HYDRATE_ARRAY,
            'a',
            function (QueryBuilder $query) {
            }
        );
    }

}