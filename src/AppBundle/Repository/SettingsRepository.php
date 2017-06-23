<?php
/**
 * SettingsRepository
 *
 */
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
     * save
     * @param Settings $settings
     */
    public function save($settings)
    {
        $this->_em->persist($settings);
        $this->_em->flush();
    }
}
