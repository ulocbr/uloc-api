<?php

namespace Uloc\ApiBundle\Repository\User;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Uloc\ApiBundle\Exception\UsernameNotFoundException;
use Uloc\ApiBundle\Entity\User\User;

class UserRepository extends EntityRepository implements UserLoaderInterface
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

    public function loadUserByUsername($username, $exception=true)
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

    /**
     * @param $login
     * @return null|object|User
     */
    public function findUserByLogin($login){

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
    public function getUserPasswordById($id){
        $dql = 'SELECT u.password FROM UlocAppBundle:User u where u.id=:id';
        $query = $this->getEntityManager()->createQuery($dql)->setParameter('id', $id)->setMaxResults(1);

        return $query->getResult();
    }


    public function findAllSimple($limit = 100, $offset = 0, $filtros = null)
    {

        $query = $this->getEntityManager()->createQueryBuilder()
            //->select('partial l.{id}, c.name')
            ->select('u, p')
            ->from("UlocAppBundle:User", "u")
            ->leftJoin('u.pessoa', 'p')
            ->where('u.roles LIKE :role and u.roles NOT LIKE :roleComitente')
            ->setParameter('role', '%"ROLE_INTRANET"%')
            ->setParameter('roleComitente', '%"ROLE_COMITENTE"%');

        $queryCount = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(1) total')
            ->from("UlocAppBundle:User", "u")
            ->leftJoin('u.pessoa', 'p')
            ->where('u.roles LIKE :role and u.roles NOT LIKE :roleComitente')
            ->setParameter('role', '%"ROLE_INTRANET"%')
            ->setParameter('roleComitente', '%"ROLE_COMITENTE"%');

        //Busca
        if (isset($filtros['busca'])) {
            $busca = $filtros['busca'];
            $filtroBuscaCriteria = Criteria::create()
                ->where(Criteria::expr()->orX(
                    Criteria::expr()->eq('u.id', $busca),
                    Criteria::expr()->contains('p.pfCpf', $busca),
                    Criteria::expr()->contains('p.pjCnpj', $busca),
                    Criteria::expr()->contains("p.name", $busca),
                    Criteria::expr()->contains("p.pjRazaoSocial", $busca)
                ));
            $query->addCriteria($filtroBuscaCriteria);
            $queryCount->addCriteria($filtroBuscaCriteria);
        }

        //Tipo
        if (isset($filtros['tipo'])) {
            $tipo = $filtros['tipo'];
            $filtroTipoCriteria = Criteria::create()
                ->where(Criteria::expr()->eq('p.tipo', $tipo));
            $query->addCriteria($filtroTipoCriteria);
            $queryCount->addCriteria($filtroTipoCriteria);
        }

        //Status
        /*if (isset($filtros['status'])) {
            $status = $filtros['status'];
            $filtroStatusCriteria = Criteria::create()
                ->where(Criteria::expr()->in('a.status', $status));
            $query->addCriteria($filtroStatusCriteria);
            $queryCount->addCriteria($filtroStatusCriteria);
        }*/

        $query = $query->getQuery()
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        //->getArrayResult();
        //return $paginator = new Paginator($query, $fetchJoinCollection = true); //https://github.com/doctrine/doctrine2/issues/2596
        return [
            'result' => $query->getArrayResult(), //getArrayResult
            'total' => $queryCount->getQuery()->getSingleScalarResult()
        ];
    }
}
