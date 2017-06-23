<?php
/**
 * LocationRepository
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Location;
use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * LocationRepository class
 *
 */
class LocationRepository extends EntityRepository
{
    /**
     * Gets all records paginated.
     *
     * @param Profile $profile Profile
     * @param int     $page    Page number
     * @return \Pagerfanta\Pagerfanta Result
     */
    public function findAllPaginated($profile, $page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryByUser($profile), false));
        $paginator->setMaxPerPage(Location::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Query all entities.
     *
     * @param $profile Profile
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryByUser($profile)
    {
        return $this->createQueryBuilder('l')
            ->where("l.profile =:profile")
            ->setParameter('profile', $profile);
    }
}
