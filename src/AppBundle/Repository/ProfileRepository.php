<?php
/**
 * ProfileRepository
 */
namespace AppBundle\Repository;

use AppBundle\Entity\Profile;
use Doctrine\ORM\EntityRepository;

/**
 * ProfileRepository
 */
class ProfileRepository extends EntityRepository
{
    /**
     * Save
     * @param Profile $profile
     */
    public function save($profile)
    {
        $this->_em->persist($profile);
        $this->_em->flush();
    }
}
