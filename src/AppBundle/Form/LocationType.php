<?php
/**
 * LocationType
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LocationType
 * @package AppBundle\Form
 */
class LocationType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('latlng', 'oh_google_maps', [
                'include_gmaps_js' => false,
            ])
            ->add('description', 'textarea', [
                'attr' => ['class' => 'tinymce', ],
            ]);
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Location',
        ));
    }

    /**
     * getBlockPrefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_location';
    }
}
