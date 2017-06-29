<?php
/**
 * NoteRepository
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Note;
use AppBundle\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * NoteRepository class
 *
 */
class NoteRepository extends EntityRepository
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
        $paginator->setMaxPerPage(Note::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Save note
     * @param Note $note
     */
    public function save(Note $note){
        $this->_em->persist($note);
        $this->_em->flush();
    }

    /**
     * Remove note
     * @param  Note $note
     */
    public function remove(Note $note){
        $this->_em->remove($note);
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
        return $this->createQueryBuilder('n')
            ->where("n.profile =:profile")
            ->setParameter('profile', $profile);
    }
}
