<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Settings;
use Doctrine\ORM\EntityRepository;

/**
 * SettingsRepository
 *
 */
class SettingsRepository extends EntityRepository
{
    /**
     * @param $settings Settings
     */
    public function save($settings)
    {
        $this->_em->persist($settings);
        $this->_em->flush();
    }
}
