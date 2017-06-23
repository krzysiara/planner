<?php
/**
 * AppBundle
 */
namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AppBundle
 * @package AppBundle
 */
class AppBundle extends Bundle
{
    /**
     * getParent
     *
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
