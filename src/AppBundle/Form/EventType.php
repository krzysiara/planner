<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ownerProfile = $options['owner'];
        $builder
            ->add('title', 'text')
            ->add('startDate', 'date')
            ->add('startTime', 'time')
            ->add('endDate', 'date')
            ->add('endTime', 'time')

            ->add('description', 'textarea')
            ->add('color', 'entity', [
                'class' => 'AppBundle:Color',
                'choice_label' => 'name',
                'multiple' => false,
                'required'=>false
            ])
            ->add('location', 'entity', [
                'class' => 'AppBundle:Location',
                'choice_label' => 'name',
                'multiple' => false,
                'required'=>false,
                'query_builder' => function (EntityRepository $er) use ($ownerProfile){
                    return $er->createQueryBuilder('l')
                        ->where('l.profile = :owner')
                        ->setParameter('owner', $ownerProfile);
                },
            ])
            ->add('participants', 'entity', [
                'class' => 'AppBundle:Contact',
                'choice_label' => 'fullname',
                'multiple' => true,
                'required'=>false,
                'query_builder' => function (EntityRepository $er) use ($ownerProfile){
                    return $er->createQueryBuilder('c')
                        ->where('c.profile = :owner')
                        ->setParameter('owner', $ownerProfile);
                },
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event',
            'owner' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_event';
    }


}
