<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * ContactRepository
 *
 */
class ContactRepository extends EntityRepository
{

    /**
     * Gets all records paginated.
     *
     * @param int             $page Page number
     * @param $profile Profile
     * @return \Pagerfanta\Pagerfanta Result
     */
    public function findAllPaginated($page = 1, $profile)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryByUser($profile), false));
        $paginator->setMaxPerPage(Contact::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Query all entities.
     *
     *  @param $profile Profile
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryByUser($profile)
    {
        return $this->createQueryBuilder('c')
            ->where("c.profile =:profile")
            ->setParameter('profile', $profile);
    }
}
