<?php

namespace Uloc\ApiBundle\Repository\User;

use Doctrine\Common\Collections\Criteria;
// use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Uloc\ApiBundle\Exception\UsernameNotFoundException;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Repository\BaseRepository;

class UserRepository extends BaseRepository implements UserLoaderInterface
{
    /**
     * @param $username
     * @return User
     */
    public function findUserByUsername($username)
    {
        return $this->findOneBy(array(
            'username' => $username
        ));
    }

    /**
     * @param $email
     * @return User
     */
    public function findUserByEmail($email)
    {
        return $this->findOneBy(array(
            'email' => $email
        ));
    }

    /**
     * @return User
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findAny()
    {
        return $this->createQueryBuilder('u')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function loadUserByUsername($username, $exception = true)
    {
        $user = $this->findUserByUsername($username);

        // allow login by email too
        if (!$user) {
            $user = $this->findUserByEmail($username);
        }

        if (!$user && $exception) {
            throw new UsernameNotFoundException(sprintf('Usuário "%s" não existe.', $username));
        }

        return $user;
    }

    public function loadUserByPersonDocument($document, $exception = true)
    {
        $documentPure = preg_replace('/\D/', '$1', trim($document));
        if (empty($documentPure)) {
            $documentPure = '000000000000000000000_dev-null';
        }
        $user = $this->getEntityManager()->createQueryBuilder()
            ->select('u, p')
            ->from(User::class, "u")
            ->join('u.person', 'p')
            ->where('p.document IN (:documents)')
            ->setParameter('documents', [$document, $documentPure])
            ->getQuery()
            ->getResult();

        if (!$user && $exception) {
            throw new UsernameNotFoundException(sprintf('Usuário com o documento "%s" não existe.', $username));
        }

        return isset($user[0]) ? $user[0] : $user;
    }

    /**
     * @param $login
     * @return null|object|User
     */
    public function findUserByLogin($login)
    {

        $usuario = $this->findOneBy(array('username' => $login));

        if (!$usuario) {
            $usuario = $this->findOneBy(array('email' => $login));
        }

        return $usuario;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserPasswordById($id)
    {
        $dql = 'SELECT u.password FROM UlocAppBundle:User u where u.id=:id';
        $query = $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->setMaxResults(1);

        return $query->getResult();
    }


    public function findAllUserSimple($limit = 100, $offset = 0, $filters = null, $hydrate = null)
    {

        $query = $this->getEntityManager()->createQueryBuilder()
            //->select('partial l.{id}, c.name')
            ->select('u, p')
            ->from(User::class, "u")
            ->leftJoin('u.person', 'p');

        $queryCount = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(1) total')
            ->from(User::class, "u")
            ->leftJoin('u.person', 'p');

        if(isset($filters['role'])){
            /*$query
                ->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%"ROLE_TEAM_MEMBERS"%');*/
            $role = $filters['role'];
            $roleCriteria = Criteria::create()
                ->where(
                    Criteria::expr()->contains("u.roles", $role)
                );
            $query->addCriteria($roleCriteria);
            $queryCount->addCriteria($roleCriteria);
        }

        //Search
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $filterSearchCriteria = Criteria::create()
                ->where(Criteria::expr()->orX(
                    Criteria::expr()->eq('u.id', $search),
                    // Criteria::expr()->contains('p.document', $search),
                    Criteria::expr()->contains("p.name", $search)
                ));
            $query->addCriteria($filterSearchCriteria);
            $queryCount->addCriteria($filterSearchCriteria);
        }

        //Type (Person/Company)
        if (isset($filters['type'])) {
            $type = $filters['type'];
            $filterTypeCriteria = Criteria::create()
                ->where(Criteria::expr()->eq('p.type', $type));
            $query->addCriteria($filterTypeCriteria);
            $queryCount->addCriteria($filterTypeCriteria);
        }

        //Active
        if (isset($filters['active'])) {
            $this->filterActive($query, $filters['active'], $queryCount);
        }

        $query = $query
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $result = $hydrate ? $query->getResult() : $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY)->getArrayResult();

        return [
            'result' => $result,
            'total' => $queryCount->getQuery()->getSingleScalarResult()
        ];
    }
}
