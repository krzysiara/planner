<?php
/**
 * RegistrationType
 * @package AppBundle\Form
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RegistrationType
 * @package AppBundle\Form
 */
class RegistrationType extends AbstractType
{
    /**
     * builForm
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('profile', ProfileType::class, ['mapped' => false]);
    }

    /**
     * getParent
     * @return string
     */
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    /**
     * getName
     * @return string
     */
    public function getName()
    {
        return 'app_user_registration';
    }
}
