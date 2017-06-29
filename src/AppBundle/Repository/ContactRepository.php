<?php
/**
 * Contact Repository
 */
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
     * @param Profile $profile Profile
     * @param int     $page    Page number
     * @return \Pagerfanta\Pagerfanta Result
     */
    public function findAllPaginated($profile, $page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryByUser($profile), false));
        $paginator->setMaxPerPage(Contact::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Remove contact
     * @param Contact $contact
     */
    public function remove(Contact $contact){
        $this->_em->remove($contact);
        $this->_em->flush();
    }

    /**
     * save contact
     * @param Contact $contact
     */
    public function save(Contact $contact){
        $this->_em->persist($contact);
        $this->_em->flush();
    }
    /**
     * Query all entities.
     *
     * @param $profile Profile
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryByUser($profile)
    {
        return $this->createQueryBuilder('c')
            ->where("c.profile =:profile")
            ->setParameter('profile', $profile);
    }
}
