<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Profile;
use Doctrine\ORM\EntityRepository;

/**
 * ProfileRepository
 *
 */
class ProfileRepository extends EntityRepository
{
    /**
     * @param $profile Profile
     */
    public function save($profile)
    {
        $this->_em->persist($profile);
        $this->_em->flush();
    }
}
