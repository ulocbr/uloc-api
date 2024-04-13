<?php

namespace Uloc\ApiBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
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
    static $defaultSort = [
        'id' => 'a.id',
        'active' => 'a.active',
    ];

    protected $fieldSearch = null;
    protected $additionalSortByPossibles = ['name' => 'a.name'];

    public function setFieldSearch($field)
    {
        $this->fieldSearch = $field;
    }

    public function setAdditionalSortByPossibles(array $fields)
    {
        $this->additionalSortByPossibles = $fields;
    }

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

        if (is_callable($sortBy)) {
            $sortBy($qb);
        } elseif (is_array($sortBy)) {
            foreach ($sortBy as $sortByItem) {
                if (is_array($sortByItem)) {
                    $sortValue = $sortByItem[0];
                    $sortOrder = $sortByItem[1];
                } else {
                    $sortValue = $sortByItem;
                    $sortOrder = $sortDesc ? Criteria::DESC : Criteria::ASC;
                }
                if (in_array($sortValue, array_keys($sortByPossibles))) {
                    $qb->addOrderBy($sortByPossibles[$sortValue], $sortOrder);
                }
            }
        } else {
            if (in_array($sortBy, array_keys($sortByPossibles))) {
                $qb->orderBy($sortByPossibles[$sortBy], $sortDesc ? Criteria::DESC : Criteria::ASC);
            } else {
                // Ordering default
                // $qb->orderBy('id', 'DESC');
            }
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

    public function processResult(Query $query, QueryBuilder $queryCount, $usePaginator = false)
    {
        if(!$usePaginator) {
            return [
                'result' => $query->getArrayResult(),
                'total' => $queryCount->getQuery()->getSingleScalarResult()
            ];
        } else {
            $paginator = new Paginator($query, true);
            return [
                'result' => (array)$paginator->getIterator(),
                'total' => count($paginator)
            ];
        }
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
        $joins = null,
        $joinsQueryCount = null,
        $usePaginator = false
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

        $queryCount = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(1) total')
            ->from($class, $defaultAlias);

        if (null !== $joins && is_callable($joins)) {
            $joins($query);
            // $joins($queryCount); BUG COUNT
        }
        if (null !== $joinsQueryCount && is_callable($joinsQueryCount)) {
            $joinsQueryCount($queryCount);
        }

        if (empty($sortByPossibles)) {
            $sortByPossibles = [
                'id' => $defaultAlias . '.id'
            ];
        }

        $this->defineSort($query, $sortBy, $sortDesc, $sortByPossibles);
        $this->defineFilterFormEntity($defaultAlias, $query, $queryCount, $filters);

        if (isset($filters['search'])) {
            if (empty($searchCriteria)) {
                $fieldSearch = !empty($this->fieldSearch) ? $this->fieldSearch : self::$defaultSearch;
                $searchCriteria = Criteria::create()->where(
                    Criteria::expr()->contains($defaultAlias . '.' . $fieldSearch, $filters['search'])
                );
            }
            $query->addCriteria($searchCriteria);
            $queryCount->addCriteria($searchCriteria);
        } else {
            if (!empty($searchCriteria)) {
                $query->addCriteria($searchCriteria);
                $queryCount->addCriteria($searchCriteria);
            }
        }

        $query = $query->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setHydrationMode($hydrationMode);

        return $this->processResult($query, $queryCount, $usePaginator);
    }

    public function findAllSimple(int $limit = 100, int $offset = 0, $sortBy = null, $sortDesc = null, array $filters = null, $onlyActive = false, $hideDeleted = true)
    {
        $sortByPossibles = array_merge(self::$defaultSort, $this->additionalSortByPossibles);
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