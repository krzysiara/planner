<?php
/**
 * Class EventType
 */

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventType
 * @package AppBundle\Form
 */
class EventType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ownerProfile = $options['owner'];
        $builder
            ->add('title', 'text')
            ->add('startDate', 'date', [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'format' => 'dd-MM-yyyy',
                'label' => 'Start date',
            ])
            ->add('startTime', 'time')
            ->add('endDate', 'date', [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'format' => 'dd-MM-yyyy',
            ])
            ->add('endTime', 'time')

            ->add('description', 'textarea', [
                'attr' => ['class' => 'tinymce', ],
            ])
            ->add('color', 'entity', [
                'class' => 'AppBundle:Color',
                'choice_label' => 'name',
                'multiple' => false,
                'required' => false,
            ])
            ->add('location', 'entity', [
                'class' => 'AppBundle:Location',
                'choice_label' => 'name',
                'multiple' => false,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($ownerProfile) {
                    return $er->createQueryBuilder('l')
                        ->where('l.profile = :owner')
                        ->setParameter('owner', $ownerProfile);
                },
            ])
            ->add('participants', 'entity', [
                'class' => 'AppBundle:Contact',
                'choice_label' => 'fullname',
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($ownerProfile) {
                    return $er->createQueryBuilder('c')
                        ->where('c.profile = :owner')
                        ->setParameter('owner', $ownerProfile);
                },
            ]);
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event',
            'owner' => null,
        ));
    }

    /**
     * getBlockPrefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_event';
    }
}
