<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * UserRepository
 *
 */
class UserRepository extends EntityRepository
{
    /**
     * @param $user User
     */
    public function save($user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Gets all records paginated.
     *
     * @param int $page Page number
     * @return \Pagerfanta\Pagerfanta Result
     */
    public function findAllPaginated($page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryAll(), false));
        $paginator->setMaxPerPage(User::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * @return array
     */
    public function findWithActiveReminderSettings()
    {
        $qb = $this->createQueryBuilder('u')
            ->join('AppBundle:Settings', 's')
            ->where("s.sendNotifications =:true")
            ->setParameter('true', true);
        return $qb->getQuery()->getResult();
    }

    /**
     * Query all entities.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryAll()
    {
        return $this->createQueryBuilder('u');
    }
}
