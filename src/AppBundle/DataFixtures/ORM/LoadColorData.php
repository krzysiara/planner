<?php
/**
 * Data fixtures for colors.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Color;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadColorData.
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadColorData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * Service container.
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface|null $container
     */
    protected $container = null;

    /**
     * Set container.
     *
     * @param ContainerInterface|null $container Service container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * load
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            'Czerwony' => 'red',
            'Zielony' => 'green',
            'Żółty' => 'yellow',
            'Niebieski' => 'blue',
            'Szary' => 'grey',
            'Biały' => 'white',
        ];

        foreach ($data as $name => $code) {
            $color = new Color();
            $color->setData($name, $code);
            $manager->persist($color);
        }
        $manager->flush();
    }
}
