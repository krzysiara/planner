<?php
/**
 * ProfileType
 * @package AppBundle\Form
 */

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfileType
 * @package AppBundle\Form
 */
class ProfileType extends AbstractType
{
    /**
     * buil form
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
                'attr' => ['class' => 'form-control', ],
            ])
            ->add('surname', 'text', [
                'attr' => ['class' => 'form-control', ],
            ])
            ->add('birthday', 'birthday', [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker form-control'],
                'format' => 'dd-MM-yyyy',
            ])
           ;
    }

    /**
     * configure options
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Profile',
            'owner_profile' => null,

        ));
    }

    /**
     * get block prefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }
}
